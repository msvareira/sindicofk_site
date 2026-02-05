#!/bin/bash

# Script para renovar certificados SSL manualmente
# Síndico FK

echo "=========================================="
echo "Renovando Certificados SSL"
echo "=========================================="

# Verificar se está rodando com permissões adequadas
if [ "$EUID" -ne 0 ] && ! groups | grep -q docker; then
    echo "⚠️  Este script precisa de permissões Docker."
    echo "Execute com: sudo ./renew-cert.sh"
    exit 1
fi

# Renovar certificados
echo "🔄 Renovando certificados..."
docker compose run --rm --entrypoint certbot certbot renew

if [ $? -eq 0 ]; then
    echo ""
    echo "✅ Certificados renovados com sucesso!"
    
    # Recarregar Nginx
    echo "🔄 Recarregando Nginx..."
    docker compose exec frontend nginx -s reload
    
    if [ $? -eq 0 ]; then
        echo "✅ Nginx recarregado com sucesso!"
        
        # Mostrar data de expiração
        echo ""
        echo "📅 Data de expiração:"
        docker compose run --rm certbot certificates
    else
        echo "⚠️  Erro ao recarregar Nginx. Tente reiniciar:"
        echo "   docker compose restart frontend"
    fi
    
    echo ""
    echo "✅ Renovação concluída!"
else
    echo ""
    echo "❌ Erro ao renovar certificados!"
    echo ""
    echo "Verifique os logs:"
    echo "   docker compose logs certbot"
fi
