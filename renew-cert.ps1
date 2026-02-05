# Script para renovar certificados SSL manualmente
# Síndico FK

Write-Host "=== Renovando Certificados SSL ===" -ForegroundColor Green

# Renovar certificados
docker-compose run --rm certbot renew

if ($LASTEXITCODE -eq 0) {
    Write-Host "`nCertificados renovados com sucesso!" -ForegroundColor Green
    
    # Recarregar Nginx
    Write-Host "Recarregando Nginx..." -ForegroundColor Yellow
    docker-compose exec frontend nginx -s reload
    
    Write-Host "Concluído!" -ForegroundColor Green
} else {
    Write-Host "`nErro ao renovar certificados!" -ForegroundColor Red
}
