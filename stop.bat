@echo off
REM Script para parar todos os containers

echo ========================================
echo   SINDICO FK - Parando Sistema
echo ========================================
echo.

docker-compose down

echo.
echo Sistema parado com sucesso!
echo.
pause
