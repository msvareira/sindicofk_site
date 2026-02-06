@echo off
echo ========================================
echo   Iniciando Sindico FK - Producao (HTTPS)
echo ========================================
echo.

REM Restaurar configuracao de producao do Nginx
echo [1/3] Restaurando configuracao de producao...
copy /Y frontend\nginx.conf frontend\nginx-temp.conf
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
echo   Ambiente de Producao Iniciado!
echo ========================================
echo.
echo   Frontend:  https://sindicofk.com.br
echo   Backend:   https://sindicofk.com.br/api
echo.
echo Para ver logs: docker-compose logs -f
echo Para parar:    docker-compose down
echo.
pause
