#!/bin/bash

echo "🚀 Iniciando setup do Backend Síndico FK..."

# Verificar se Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está rodando. Por favor, inicie o Docker Desktop."
    exit 1
fi

echo "✓ Docker está rodando"

# Verificar se já existe projeto Laravel
if [ ! -f "composer.json" ]; then
    echo "📦 Instalando Laravel..."
    docker run --rm -v $(pwd):/app composer create-project laravel/laravel .
else
    echo "✓ Laravel já instalado"
fi

# Criar arquivo .env se não existir
if [ ! -f ".env" ]; then
    echo "📝 Criando arquivo .env..."
    cp .env.example .env
else
    echo "✓ Arquivo .env já existe"
fi

# Subir containers
echo "🐳 Subindo containers Docker..."
docker-compose up -d

# Aguardar containers iniciarem
echo "⏳ Aguardando containers iniciarem..."
sleep 10

# Gerar key se necessário
echo "🔑 Gerando application key..."
docker exec sindicofk_app php artisan key:generate

# Executar migrations
echo "📊 Executando migrations..."
docker exec sindicofk_app php artisan migrate --force

# Limpar cache
echo "🧹 Limpando cache..."
docker exec sindicofk_app php artisan config:clear
docker exec sindicofk_app php artisan cache:clear

echo ""
echo "✅ Setup concluído com sucesso!"
echo ""
echo "📍 Acessos:"
echo "   - API: http://localhost:8080"
echo "   - PHPMyAdmin: http://localhost:8081"
echo ""
echo "📚 Comandos úteis:"
echo "   - Ver logs: docker-compose logs -f app"
echo "   - Parar: docker-compose down"
echo "   - Reiniciar: docker-compose restart"
echo ""
