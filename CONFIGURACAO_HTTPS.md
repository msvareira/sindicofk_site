# Configuração HTTPS - Síndico FK

## Domínio Configurado
- **Domínio Principal:** sindicofk.com.br
- **Domínio Alternativo:** www.sindicofk.com.br
- **Email Administrativo:** adm.sindicofk@gmail.com

## Pré-requisitos

### 1. Configuração de DNS
Certifique-se de que os domínios estão apontando para o IP do seu servidor:

```
sindicofk.com.br     A    SEU_IP_AQUI
www.sindicofk.com.br A    SEU_IP_AQUI
```

Aguarde a propagação do DNS (pode levar até 48 horas).

### 2. Firewall
Abra as portas necessárias:

```powershell
# Porta 80 (HTTP)
New-NetFirewallRule -DisplayName "HTTP" -Direction Inbound -LocalPort 80 -Protocol TCP -Action Allow

# Porta 443 (HTTPS)
New-NetFirewallRule -DisplayName "HTTPS" -Direction Inbound -LocalPort 443 -Protocol TCP -Action Allow
```

## Instalação do Certificado SSL

### Primeira Vez

1. **Verifique o DNS:**
```powershell
nslookup sindicofk.com.br
nslookup www.sindicofk.com.br
```

2. **Execute o script de inicialização:**
```powershell
.\init-letsencrypt.ps1
```

3. **O script irá:**
   - Criar diretórios necessários
   - Parar containers existentes
   - Solicitar certificado SSL ao Let's Encrypt
   - Configurar renovação automática
   - Reiniciar os containers

4. **Acesse seu site:**
   - https://sindicofk.com.br
   - https://www.sindicofk.com.br

### Modo de Teste (Recomendado Primeiro)

Se quiser testar sem usar o limite de requisições do Let's Encrypt:

1. Edite `init-letsencrypt.ps1`
2. Altere `$staging = 0` para `$staging = 1`
3. Execute o script
4. Após verificar que funciona, execute novamente com `$staging = 0`

## Renovação do Certificado

### Automática
O certificado é renovado automaticamente a cada 12 horas pelo container `certbot`.

### Manual
Para renovar manualmente:

```powershell
.\renew-cert.ps1
```

## Estrutura de Arquivos

```
Site/
├── certbot/
│   ├── conf/           # Certificados SSL
│   └── www/            # Challenge files para Let's Encrypt
├── frontend/
│   └── nginx.conf      # Configuração Nginx com HTTPS
├── docker-compose.yml  # Containers configurados
├── init-letsencrypt.ps1   # Script de instalação
└── renew-cert.ps1      # Script de renovação manual
```

## Verificação

### Verificar Certificado
```powershell
# Ver informações do certificado
docker-compose run --rm certbot certificates

# Verificar expiração
openssl s_client -connect sindicofk.com.br:443 -servername sindicofk.com.br | openssl x509 -noout -dates
```

### Logs do Nginx
```powershell
docker-compose logs frontend
```

### Logs do Certbot
```powershell
docker-compose logs certbot
```

## Solução de Problemas

### Erro: "DNS resolution failed"
- Verifique se o domínio está apontando para o IP correto
- Aguarde a propagação do DNS (24-48h)

### Erro: "Connection refused"
- Verifique se as portas 80 e 443 estão abertas no firewall
- Certifique-se de que nenhum outro serviço está usando essas portas

### Erro: "Rate limit exceeded"
- Use o modo staging (`$staging = 1`) para testes
- Let's Encrypt limita 5 certificados por semana por domínio

### Certificado não carrega
```powershell
# Verificar se os arquivos existem
ls certbot/conf/live/sindicofk.com.br/

# Reiniciar nginx
docker-compose restart frontend
```

## Segurança

### Headers de Segurança
O Nginx já está configurado com:
- X-Frame-Options: SAMEORIGIN
- X-Content-Type-Options: nosniff
- X-XSS-Protection: 1; mode=block

### Protocolos SSL
Configurado para usar apenas:
- TLSv1.2
- TLSv1.3

### Teste de Segurança SSL
Teste a configuração SSL em:
- https://www.ssllabs.com/ssltest/analyze.html?d=sindicofk.com.br

## Manutenção

### Backup dos Certificados
```powershell
# Fazer backup
Compress-Archive -Path certbot/conf -DestinationPath "backup-ssl-$(Get-Date -Format 'yyyy-MM-dd').zip"
```

### Restaurar Certificados
```powershell
# Restaurar do backup
Expand-Archive -Path "backup-ssl-2026-02-05.zip" -DestinationPath certbot/conf -Force
docker-compose restart frontend
```

## Recursos

- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)
- [Certbot Documentation](https://certbot.eff.org/docs/)
- [Nginx SSL Configuration](https://nginx.org/en/docs/http/configuring_https_servers.html)
