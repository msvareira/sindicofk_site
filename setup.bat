@echo off
REM Script de primeira configuração do backend

echo ========================================
echo   SINDICO FK - Configuracao Inicial
echo ========================================
echo.

echo Verificando se containers estao rodando...
docker-compose ps | findstr "backend" >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERRO] Backend nao esta rodando!
    echo Execute start.bat primeiro.
    pause
    exit /b 1
)

echo.
echo Instalando dependencias do Composer...
docker-compose exec backend composer install

echo.
echo Gerando chave da aplicacao...
docker-compose exec backend php artisan key:generate

echo.
echo Executando migrations...
docker-compose exec backend php artisan migrate

echo.
echo Limpando cache...
docker-compose exec backend php artisan config:clear
docker-compose exec backend php artisan cache:clear

echo.
echo ========================================
echo   Configuracao concluida!
echo ========================================
echo.
echo O sistema esta pronto para uso.
echo.
pause
