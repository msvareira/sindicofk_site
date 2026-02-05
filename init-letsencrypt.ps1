# Script para inicializar certificados SSL com Let's Encrypt
# Síndico FK - sindicofk.com.br

$domains = @("sindicofk.com.br", "www.sindicofk.com.br")
$email = "adm.sindicofk@gmail.com"
$staging = 0  # Defina como 1 para modo de teste

Write-Host "=== Configurando HTTPS para Síndico FK ===" -ForegroundColor Green
Write-Host "Domínios: $($domains -join ', ')" -ForegroundColor Cyan
Write-Host "Email: $email" -ForegroundColor Cyan

# Criar diretórios necessários
Write-Host "`nCriando diretórios..." -ForegroundColor Yellow
New-Item -ItemType Directory -Force -Path "certbot/conf" | Out-Null
New-Item -ItemType Directory -Force -Path "certbot/www" | Out-Null

# Construir argumentos de domínio
$domainArgs = $domains | ForEach-Object { "-d $_ " }
$stagingArg = if ($staging -eq 1) { "--staging" } else { "" }

Write-Host "Domínio deve estar apontando para este servidor!" -ForegroundColor Yellow
Write-Host "Pressione qualquer tecla para continuar ou Ctrl+C para cancelar..."
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

# Parar containers se estiverem rodando
Write-Host "`nParando containers..." -ForegroundColor Yellow
docker-compose down

# Subir apenas frontend e backend temporariamente
Write-Host "Subindo containers..." -ForegroundColor Yellow
docker-compose up -d frontend backend

# Aguardar nginx iniciar
Write-Host "Aguardando Nginx iniciar..." -ForegroundColor Yellow
Start-Sleep -Seconds 5

# Obter certificado
Write-Host "`nSolicitando certificado SSL..." -ForegroundColor Yellow
$certbotCmd = "docker-compose run --rm certbot certonly --webroot --webroot-path=/var/www/certbot --email $email --agree-tos --no-eff-email $stagingArg $domainArgs"
Write-Host "Comando: $certbotCmd" -ForegroundColor Gray
Invoke-Expression $certbotCmd

if ($LASTEXITCODE -eq 0) {
    Write-Host "`n=== Certificado obtido com sucesso! ===" -ForegroundColor Green
    
    # Recarregar Nginx
    Write-Host "Recarregando Nginx..." -ForegroundColor Yellow
    docker-compose restart frontend
    
    # Subir certbot para renovação automática
    Write-Host "Iniciando serviço de renovação automática..." -ForegroundColor Yellow
    docker-compose up -d certbot
    
    Write-Host "`n=== Configuração concluída! ===" -ForegroundColor Green
    Write-Host "Seu site agora está disponível em:" -ForegroundColor Cyan
    Write-Host "  - https://sindicofk.com.br" -ForegroundColor White
    Write-Host "  - https://www.sindicofk.com.br" -ForegroundColor White
    Write-Host "`nO certificado será renovado automaticamente a cada 12 horas." -ForegroundColor Cyan
} else {
    Write-Host "`n=== Erro ao obter certificado! ===" -ForegroundColor Red
    Write-Host "Verifique se:" -ForegroundColor Yellow
    Write-Host "  1. Os domínios estão apontando para este servidor" -ForegroundColor White
    Write-Host "  2. As portas 80 e 443 estão abertas no firewall" -ForegroundColor White
    Write-Host "  3. O DNS está propagado (pode levar até 48h)" -ForegroundColor White
    Write-Host "`nPara testar, execute novamente com staging=1" -ForegroundColor Yellow
}
