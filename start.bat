@echo off
REM Script de inicialização do Síndico FK
REM Execute este arquivo para iniciar todo o sistema

echo ========================================
echo   SINDICO FK - Inicializacao
echo ========================================
echo.

REM Verificar se Docker está rodando
docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERRO] Docker nao esta rodando!
    echo Por favor, inicie o Docker Desktop e tente novamente.
    pause
    exit /b 1
)

echo [OK] Docker esta rodando
echo.

REM Parar containers existentes
echo Parando containers anteriores...
docker-compose down

echo.
echo Iniciando containers...
docker-compose up -d

if %errorlevel% neq 0 (
    echo [ERRO] Falha ao iniciar containers
    pause
    exit /b 1
)

echo.
echo ========================================
echo   Aguardando servicos iniciarem...
echo ========================================
timeout /t 10 /nobreak >nul

echo.
echo ========================================
echo   SISTEMA PRONTO!
echo ========================================
echo.
echo Acesse:
echo   Frontend:  http://localhost
echo   Backend:   http://localhost:8080
echo   Mailpit:   http://localhost:8025
echo.
echo Para ver logs:     docker-compose logs -f
echo Para parar:        docker-compose down
echo.

pause
