@echo off
echo Iniciando setup do Backend Sindico FK...

REM Verificar se Docker esta rodando
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker nao esta rodando. Por favor, inicie o Docker Desktop.
    pause
    exit /b 1
)

echo Docker esta rodando

REM Verificar se ja existe projeto Laravel
if not exist "composer.json" (
    echo Instalando Laravel...
    docker run --rm -v %cd%:/app composer create-project laravel/laravel .
) else (
    echo Laravel ja instalado
)

REM Criar arquivo .env se nao existir
if not exist ".env" (
    echo Criando arquivo .env...
    copy .env.example .env
) else (
    echo Arquivo .env ja existe
)

REM Subir containers
echo Subindo containers Docker...
docker-compose up -d

REM Aguardar containers iniciarem
echo Aguardando containers iniciarem...
timeout /t 10 /nobreak

REM Gerar key se necessario
echo Gerando application key...
docker exec sindicofk_app php artisan key:generate

REM Executar migrations
echo Executando migrations...
docker exec sindicofk_app php artisan migrate --force

REM Limpar cache
echo Limpando cache...
docker exec sindicofk_app php artisan config:clear
docker exec sindicofk_app php artisan cache:clear

echo.
echo Setup concluido com sucesso!
echo.
echo Acessos:
echo    - API: http://localhost:8080
echo    - PHPMyAdmin: http://localhost:8081
echo.
echo Comandos uteis:
echo    - Ver logs: docker-compose logs -f app
echo    - Parar: docker-compose down
echo    - Reiniciar: docker-compose restart
echo.
pause
