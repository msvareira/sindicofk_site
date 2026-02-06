#!/bin/bash

# Script para Deploy no VPS (Produção com HTTPS)
# Execute este script no VPS após git pull

echo "========================================"
echo "  Deploy Produção - Síndico FK (VPS)"
echo "========================================"
echo ""

# Garantir que não existe nginx-temp.conf (arquivo local)
echo "[1/4] Removendo configurações locais..."
rm -f frontend/nginx-temp.conf
echo "✓ Configuração local removida"

# Parar containers
echo ""
echo "[2/4] Parando containers..."
docker-compose down

# Limpar imagens antigas
echo ""
echo "[3/4] Limpando cache Docker..."
docker system prune -f

# Iniciar com build
echo ""
echo "[4/4] Iniciando containers de produção..."
docker-compose up -d --build

echo ""
echo "========================================"
echo "  Deploy Concluído!"
echo "========================================"
echo ""
echo "  Frontend:  https://sindicofk.com.br"
echo "  Backend:   https://sindicofk.com.br/api"
echo ""
echo "Comandos úteis:"
echo "  Ver logs:     docker-compose logs -f"
echo "  Ver status:   docker-compose ps"
echo "  Reiniciar:    docker-compose restart"
echo ""
