@echo off
echo ========================================
echo   Iniciando Sindico FK - Ambiente Local
echo ========================================
echo.

REM Copiar configuracao local do Nginx
echo [1/3] Copiando configuracao local do Nginx...
copy /Y frontend\nginx-local.conf frontend\nginx-temp.conf
if errorlevel 1 (
    echo ERRO: Falha ao copiar configuracao
    pause
    exit /b 1
)

REM Parar containers existentes
echo.
echo [2/3] Parando containers existentes...
docker-compose down

REM Iniciar containers com build
echo.
echo [3/3] Iniciando containers...
docker-compose up -d --build

echo.
echo ========================================
echo   Ambiente Local Iniciado!
echo ========================================
echo.
echo   Frontend:  http://localhost:81
echo   Backend:   http://localhost:8080
echo   MySQL:     localhost:3306
echo   Redis:     localhost:6379
echo   Mailpit:   http://localhost:8025
echo.
echo Para ver logs: docker-compose logs -f
echo Para parar:    docker-compose down
echo.
pause
