#!/bin/bash

# Script SIMPLIFICADO para obter certificado SSL
# Passo a passo manual para troubleshooting

domains=(sindicofk.com.br www.sindicofk.com.br)
email="adm.sindicofk@gmail.com"

echo "=========================================="
echo "🔒 Obter Certificado SSL - Método Manual"
echo "=========================================="
echo ""

# 1. Criar diretórios
echo "📁 Criando diretórios..."
mkdir -p certbot/conf
mkdir -p certbot/www
chmod -R 755 certbot/

# 2. Backup do nginx.conf atual
echo "💾 Fazendo backup do nginx.conf..."
cp frontend/nginx.conf frontend/nginx.conf.backup

# 3. Usar configuração temporária (sem HTTPS redirect)
echo "🔧 Usando configuração temporária..."
cp frontend/nginx-temp.conf frontend/nginx.conf

# 4. Parar e reconstruir
echo "🛑 Parando containers..."
docker compose down

echo "🚀 Subindo com configuração temporária..."
docker compose up -d frontend backend mysql redis

# 5. Aguardar nginx
echo "⏳ Aguardando Nginx iniciar..."
sleep 10

# 6. Testar se nginx está acessível
echo "🔍 Testando acesso HTTP..."
curl -I http://sindicofk.com.br || echo "⚠️  Site não acessível"

# 7. Obter certificado
echo ""
echo "🔐 Solicitando certificado..."
docker compose run --rm --entrypoint certbot certbot certonly \
    --webroot \
    --webroot-path=/var/www/certbot \
    --email $email \
    --agree-tos \
    --no-eff-email \
    -d sindicofk.com.br \
    -d www.sindicofk.com.br

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Certificado obtido!"
    echo ""
    
    # 8. Restaurar nginx.conf com HTTPS
    echo "🔧 Restaurando configuração com HTTPS..."
    cp frontend/nginx.conf.backup frontend/nginx.conf
    
    # 9. Reiniciar tudo
    echo "🔄 Reiniciando containers..."
    docker compose down
    docker compose up -d
    
    echo ""
    echo "=========================================="
    echo "✅ Configuração HTTPS completa!"
    echo "=========================================="
    echo ""
    echo "🌐 Acesse: https://sindicofk.com.br"
    echo ""
else
    echo ""
    echo "❌ Falhou! Restaurando configuração original..."
    cp frontend/nginx.conf.backup frontend/nginx.conf
    docker compose restart frontend
    
    echo ""
    echo "Diagnóstico:"
    echo "1. Verificar se nginx está rodando: docker compose ps"
    echo "2. Ver logs: docker compose logs frontend"
    echo "3. Testar acesso: curl -I http://sindicofk.com.br"
    echo "4. Verificar permissões: ls -la certbot/"
fi
