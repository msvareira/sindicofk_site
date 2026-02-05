#!/bin/bash

# Script para inicializar certificados SSL com Let's Encrypt
# Síndico FK - sindicofk.com.br
# Ubuntu 24.04 com Docker

# Configurações
domains=(sindicofk.com.br www.sindicofk.com.br)
email="adm.sindicofk@gmail.com"
staging=0  # Defina como 1 para modo de teste

echo "=========================================="
echo "🔒 Configurando HTTPS para Síndico FK"
echo "=========================================="
echo "Domínios: ${domains[@]}"
echo "Email: $email"
echo "Modo Staging: $staging"
echo ""

# Verificar se está rodando como root ou com sudo
if [ "$EUID" -ne 0 ] && ! groups | grep -q docker; then
    echo "⚠️  Este script precisa de permissões Docker."
    echo "Execute com: sudo ./init-letsencrypt.sh"
    echo "Ou adicione seu usuário ao grupo docker: sudo usermod -aG docker $USER"
    exit 1
fi

# Criar diretórios necessários
echo "📁 Criando diretórios..."
mkdir -p certbot/conf
mkdir -p certbot/www

# Verificar DNS antes de continuar
echo ""
echo "🔍 Verificando DNS..."
for domain in "${domains[@]}"; do
    ip=$(dig +short $domain | tail -n1)
    if [ -z "$ip" ]; then
        echo "❌ Erro: Domínio $domain não está resolvendo!"
        echo "Configure o DNS antes de continuar."
        exit 1
    else
        echo "✅ $domain -> $ip"
    fi
done

echo ""
echo "⚠️  IMPORTANTE:"
echo "- Seu domínio deve estar apontando para este servidor"
echo "- As portas 80 e 443 devem estar abertas no firewall"
echo "- Verifique com: sudo ufw status"
echo ""
read -p "Pressione ENTER para continuar ou Ctrl+C para cancelar..."

# Parar containers se estiverem rodando
echo ""
echo "🛑 Parando containers..."
docker compose down

# Construir argumentos de domínio
domain_args=""
for domain in "${domains[@]}"; do
    domain_args="$domain_args -d $domain"
done

# Argumento de staging
staging_arg=""
if [ $staging != "0" ]; then
    staging_arg="--staging"
    echo "⚠️  Modo STAGING ativado (teste)"
fi

# Subir apenas frontend e backend temporariamente
echo ""
echo "🚀 Subindo containers..."
docker compose up -d frontend backend mysql redis

# Aguardar nginx iniciar
echo ""
echo "⏳ Aguardando Nginx iniciar..."
sleep 10

# Verificar se frontend está rodando
if ! docker compose ps frontend | grep -q "Up"; then
    echo "❌ Erro: Container frontend não está rodando!"
    echo "Verifique os logs: docker compose logs frontend"
    exit 1
fi

# Obter certificado
echo ""
echo "🔐 Solicitando certificado SSL do Let's Encrypt..."
echo ""

docker compose run --rm certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email $email \
    --agree-tos \
    --no-eff-email \
    $staging_arg \
    $domain_args

if [ $? -eq 0 ]; then
    echo ""
    echo "=========================================="
    echo "✅ Certificado obtido com sucesso!"
    echo "=========================================="
    
    # Recarregar Nginx
    echo "🔄 Recarregando Nginx..."
    docker compose restart frontend
    
    # Subir certbot para renovação automática
    echo "🔄 Iniciando serviço de renovação automática..."
    docker compose up -d certbot
    
    echo ""
    echo "=========================================="
    echo "✅ Configuração concluída!"
    echo "=========================================="
    echo ""
    echo "🌐 Seu site agora está disponível em:"
    echo "   - https://sindicofk.com.br"
    echo "   - https://www.sindicofk.com.br"
    echo ""
    echo "📝 O certificado será renovado automaticamente a cada 12 horas."
    echo ""
    echo "🔍 Comandos úteis:"
    echo "   - Ver logs: docker compose logs -f frontend"
    echo "   - Ver certificados: docker compose run --rm certbot certificates"
    echo "   - Testar SSL: curl -I https://sindicofk.com.br"
    echo ""
else
    echo ""
    echo "=========================================="
    echo "❌ Erro ao obter certificado!"
    echo "=========================================="
    echo ""
    echo "Verifique se:"
    echo "  1. Os domínios estão apontando para este servidor"
    echo "  2. As portas 80 e 443 estão abertas no firewall (sudo ufw status)"
    echo "  3. O DNS está propagado (pode levar até 48h)"
    echo "  4. Não há outro serviço usando as portas 80/443"
    echo ""
    echo "Comandos de diagnóstico:"
    echo "  - Verificar DNS: dig sindicofk.com.br +short"
    echo "  - Verificar portas: sudo ss -tulpn | grep -E ':(80|443)'"
    echo "  - Ver logs: docker compose logs frontend"
    echo ""
    echo "💡 Para testar, execute novamente com staging=1 no script"
    exit 1
fi
