# Configuração HTTPS - Síndico FK

## Ambiente
- **Sistema Operacional:** Ubuntu 24.04 LTS
- **Infraestrutura:** VPS com Docker Containers
- **Domínio Principal:** sindicofk.com.br
- **Domínio Alternativo:** www.sindicofk.com.br
- **Email Administrativo:** adm.sindicofk@gmail.com

## Pré-requisitos

### 1. Configuração de DNS
Certifique-se de que os domínios estão apontando para o IP do seu VPS:

```bash
sindicofk.com.br     A    SEU_IP_VPS
www.sindicofk.com.br A    SEU_IP_VPS
```

Aguarde a propagação do DNS (pode levar até 48 horas).

### 2. Firewall (UFW - Ubuntu)
Abra as portas necessárias no firewall do Ubuntu:

```bash
# Verificar status do firewall
sudo ufw status

# Permitir SSH (importante para não perder acesso!)
sudo ufw allow 22/tcp

# Porta 80 (HTTP - necessária para Let's Encrypt)
sudo ufw allow 80/tcp

# Porta 443 (HTTPS)
sudo ufw allow 443/tcp

# Ativar firewall se ainda não estiver ativo
sudo ufw enable

# Verificar regras
sudo ufw status numbered
```

### 3. Docker e Docker Compose
Certifique-se de que Docker e Docker Compose estão instalados:

```bash
# Verificar versões
docker --version
docker compose version

# Se não estiver instalado, instalar:
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Instalar Docker Compose (se necessário)
sudo apt-get update
sudo apt-get install docker-compose-plugin
```

## Instalação do Certificado SSL

### Primeira Vez

1. **Verifique o DNS:**
```bash
# Verificar se o DNS está apontando corretamente
nslookup sindicofk.com.br
nslookup www.sindicofk.com.br

# Ou usar dig
dig sindicofk.com.br +short
dig www.sindicofk.com.br +short
```

2. **Execute o script de inicialização:**
```bash
# Dar permissão de execução ao script
chmod +x init-letsencrypt.sh

# Executar o script
./init-letsencrypt.sh
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

1. Edite `init-letsencrypt.sh`
2. Altere `staging=0` para `staging=1`
3. Execute o script
4. Após verificar que funciona, execute novamente com `staging=0`

## Renovação do Certificado

### Automática
O certificado é renovado automaticamente a cada 12 horas pelo container `certbot`.

### Manual
Para renovar manualmente:

```bash
# Executar script de renovação
./renew-cert.sh

# Ou executar diretamente
docker compose run --rm certbot renew
docker compose exec frontend nginx -s reload
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
├── init-letsencrypt.sh    # Script de instalação (Linux)
└── renew-cert.sh       # Script de renovação manual (Linux)
```

## Verificação

### Verificar Certificado
```bash
# Ver informações do certificado
docker compose run --rm certbot certificates

# Verificar expiração
echo | openssl s_client -connect sindicofk.com.br:443 -servername sindicofk.com.br 2>/dev/null | openssl x509 -noout -dates

# Verificar detalhes completos
echo | openssl s_client -connect sindicofk.com.br:443 -servername sindicofk.com.br 2>/dev/null | openssl x509 -noout -text
```

### Logs do Nginx
```bash
# Ver logs em tempo real
docker compose logs -f frontend

# Últimas 50 linhas
docker compose logs --tail=50 frontend
```

### Logs do Certbot
```bash
# Ver logs do certbot
docker compose logs certbot

# Logs dentro do container
docker compose exec frontend cat /var/log/nginx/error.log
docker compose exec frontend cat /var/log/nginx/access.log
```

### Status dos Containers
```bash
# Ver todos os containers
docker compose ps

# Ver recursos utilizados
docker stats

# Ver detalhes de um container
docker compose exec frontend nginx -v
```

## Solução de Problemas

### Erro: "DNS resolution failed"
- Verifique se o domínio está apontando para o IP correto
- Aguarde a propagação do DNS (24-48h)
```bash
# Testar DNS de diferentes servidores
dig @8.8.8.8 sindicofk.com.br +short
dig @1.1.1.1 sindicofk.com.br +short
```

### Erro: "Connection refused"
- Verifique se as portas 80 e 443 estão abertas no firewall
```bash
# Verificar portas abertas
sudo ss -tulpn | grep :80
sudo ss -tulpn | grep :443

# Verificar firewall
sudo ufw status

# Testar se portas estão acessíveis de fora
curl -I http://sindicofk.com.br
```

- Certifique-se de que nenhum outro serviço está usando essas portas
```bash
# Ver o que está usando a porta 80
sudo lsof -i :80

# Ver o que está usando a porta 443
sudo lsof -i :443
```

### Erro: "Rate limit exceeded"
- Use o modo staging (`staging=1`) para testes
- Let's Encrypt limita 5 certificados por semana por domínio
```bash
# Verificar limites atuais
curl -s https://crt.sh/?q=sindicofk.com.br | grep "sindicofk"
```

### Certificado não carrega
```bash
# Verificar se os arquivos existem
ls -la certbot/conf/live/sindicofk.com.br/

# Verificar permissões
sudo chown -R $(whoami):$(whoami) certbot/

# Reiniciar nginx
docker compose restart frontend

# Ver logs de erro
docker compose logs frontend | grep -i error
```

### Container não inicia
```bash
# Ver logs detalhados
docker compose logs frontend

# Testar configuração do nginx
docker compose exec frontend nginx -t

# Reconstruir container
docker compose down
docker compose build --no-cache frontend
docker compose up -d
```

### Porta 80/443 já em uso
```bash
# Parar serviços que podem estar usando as portas
sudo systemctl stop apache2
sudo systemctl stop nginx
sudo systemctl disable apache2
sudo systemctl disable nginx

# Verificar novamente
sudo ss -tulpn | grep -E ':(80|443)'
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
```bash
# Fazer backup
tar -czf backup-ssl-$(date +%Y-%m-%d).tar.gz certbot/conf

# Listar backups
ls -lh backup-ssl-*.tar.gz

# Backup para local remoto (opcional)
scp backup-ssl-$(date +%Y-%m-%d).tar.gz user@backup-server:/backups/
```

### Restaurar Certificados
```bash
# Restaurar do backup
tar -xzf backup-ssl-2026-02-05.tar.gz

# Ajustar permissões
sudo chown -R root:root certbot/conf

# Reiniciar frontend
docker compose restart frontend
```

### Monitoramento
```bash
# Verificar expiração (crie um cron job)
cat > check-ssl-expiry.sh << 'EOF'
#!/bin/bash
DAYS=$(echo | openssl s_client -connect sindicofk.com.br:443 -servername sindicofk.com.br 2>/dev/null | openssl x509 -noout -enddate | cut -d= -f2 | xargs -I{} date -d {} +%s)
NOW=$(date +%s)
DIFF=$(( ($DAYS - $NOW) / 86400 ))
echo "Certificado expira em $DIFF dias"
if [ $DIFF -lt 30 ]; then
    echo "ATENÇÃO: Certificado expirando em breve!"
fi
EOF

chmod +x check-ssl-expiry.sh
./check-ssl-expiry.sh
```

### Atualização dos Containers
```bash
# Atualizar imagens
docker compose pull

# Reconstruir e reiniciar
docker compose up -d --build

# Limpar imagens antigas
docker image prune -a
```

## Comandos Úteis Ubuntu + Docker

### Gerenciamento do Sistema
```bash
# Ver uso de disco
df -h

# Ver uso de memória
free -h

# Ver processos Docker
docker ps -a

# Ver uso de recursos
docker stats --no-stream
```

### Gerenciamento de Logs
```bash
# Limpar logs do Docker
sudo sh -c "truncate -s 0 /var/lib/docker/containers/*/*-json.log"

# Verificar tamanho dos logs
sudo du -sh /var/lib/docker/containers/*/

# Configurar rotação de logs (editar /etc/docker/daemon.json)
sudo nano /etc/docker/daemon.json
# Adicionar:
# {
#   "log-driver": "json-file",
#   "log-opts": {
#     "max-size": "10m",
#     "max-file": "3"
#   }
# }

# Reiniciar Docker
sudo systemctl restart docker
```

### Iniciar Containers Automaticamente no Boot
```bash
# Habilitar Docker no boot
sudo systemctl enable docker

# Os containers já estão configurados com restart: unless-stopped
# Verifique no docker-compose.yml
```

### Firewall Adicional (Opcional - Se usar provedor de VPS)
```bash
# Alguns VPS têm firewall externo (Vultr, DigitalOcean, etc.)
# Certifique-se de abrir as portas no painel do provedor também:
# - Porta 22 (SSH)
# - Porta 80 (HTTP)
# - Porta 443 (HTTPS)
```

## Checklist de Deploy Completo

### Antes do Deploy

- [ ] VPS Ubuntu 24.04 provisionado
- [ ] Docker e Docker Compose instalados
- [ ] Domínio registrado (sindicofk.com.br)
- [ ] DNS configurado apontando para IP do VPS
- [ ] DNS propagado (verificar com `dig` ou `nslookup`)
- [ ] Firewall UFW configurado (portas 22, 80, 443)
- [ ] Firewall do provedor configurado (se aplicável)
- [ ] Acesso SSH funcionando
- [ ] Código do site no servidor

### Durante o Deploy

- [ ] `cd` para o diretório do projeto
- [ ] Executar `chmod +x init-letsencrypt.sh renew-cert.sh`
- [ ] Verificar `docker-compose.yml`
- [ ] Executar `./init-letsencrypt.sh`
- [ ] Aguardar certificado ser emitido
- [ ] Verificar containers: `docker compose ps`
- [ ] Testar HTTP→HTTPS redirect
- [ ] Testar site: `curl -I https://sindicofk.com.br`

### Após o Deploy

- [ ] Acessar site via browser (https://sindicofk.com.br)
- [ ] Testar formulários
- [ ] Verificar console do browser (F12) - sem erros
- [ ] Testar API: `curl https://sindicofk.com.br/api/health`
- [ ] Verificar SSL Score: https://www.ssllabs.com/ssltest/
- [ ] Configurar backup automático dos certificados
- [ ] Documentar credenciais e acessos
- [ ] Configurar monitoramento (opcional)

## Segurança Adicional

### Fail2ban (Proteção contra Brute Force)
```bash
# Instalar Fail2ban
sudo apt update
sudo apt install fail2ban -y

# Configurar para proteger SSH
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo nano /etc/fail2ban/jail.local

# Iniciar serviço
sudo systemctl start fail2ban
sudo systemctl enable fail2ban

# Ver status
sudo fail2ban-client status
```

### Atualizações Automáticas
```bash
# Instalar unattended-upgrades
sudo apt install unattended-upgrades -y

# Configurar
sudo dpkg-reconfigure --priority=low unattended-upgrades
```

### Swap (Se VPS tiver pouca RAM)
```bash
# Criar arquivo swap de 2GB
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile

# Tornar permanente
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab

# Verificar
free -h
```

## Recursos

- [Let's Encrypt Documentation](https://letsencrypt.org/docs/)
- [Certbot Documentation](https://certbot.eff.org/docs/)
- [Nginx SSL Configuration](https://nginx.org/en/docs/http/configuring_https_servers.html)
- [Docker Documentation](https://docs.docker.com/)
- [Ubuntu Server Guide](https://ubuntu.com/server/docs)
- [UFW Firewall Guide](https://help.ubuntu.com/community/UFW)
