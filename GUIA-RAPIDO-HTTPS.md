# Guia Rápido - Deploy com HTTPS no VPS Ubuntu

## 🚀 Início Rápido (5 passos)

### 1. Preparar VPS
```bash
# Atualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Instalar Docker Compose
sudo apt install docker-compose-plugin -y

# Fazer logout e login novamente para aplicar grupo docker
```

### 2. Configurar Firewall
```bash
# Configurar UFW
sudo ufw allow 22/tcp   # SSH
sudo ufw allow 80/tcp   # HTTP
sudo ufw allow 443/tcp  # HTTPS
sudo ufw enable
sudo ufw status
```

### 3. Clonar/Upload Projeto
```bash
# Opção A: Git
git clone seu-repositorio.git
cd Site

# Opção B: SCP/SFTP
# Upload do projeto via FileZilla ou WinSCP
```

### 4. Verificar DNS
```bash
# Verificar se domínio aponta para este servidor
dig sindicofk.com.br +short
dig www.sindicofk.com.br +short

# Deve retornar o IP do seu VPS
```

### 5. Executar Setup HTTPS
```bash
# Dar permissão aos scripts
chmod +x init-letsencrypt.sh renew-cert.sh

# Executar instalação
./init-letsencrypt.sh

# Aguardar conclusão
```

## ✅ Pronto!

Acesse: **https://sindicofk.com.br**

---

## 📋 Checklist Pré-Deploy

Antes de executar `./init-letsencrypt.sh`:

- [ ] VPS provisionado com Ubuntu 24.04
- [ ] Docker instalado
- [ ] Firewall configurado (UFW)
- [ ] Domínio sindicofk.com.br apontando para IP do VPS
- [ ] DNS propagado (pode levar até 48h)
- [ ] Código do site no servidor
- [ ] Arquivos `docker-compose.yml` e `nginx.conf` corretos

## 🔍 Verificações Pós-Deploy

```bash
# 1. Containers rodando
docker compose ps

# 2. Logs sem erros
docker compose logs frontend
docker compose logs backend

# 3. HTTPS funcionando
curl -I https://sindicofk.com.br

# 4. Certificado válido
docker compose run --rm certbot certificates
```

## 🐛 Solução Rápida de Problemas

### DNS não resolvendo
```bash
# Aguardar propagação ou verificar configuração no registrador
dig sindicofk.com.br +short
```

### Porta 80/443 em uso
```bash
# Parar Apache/Nginx do sistema
sudo systemctl stop apache2 nginx
sudo systemctl disable apache2 nginx
```

### Container não inicia
```bash
# Ver logs
docker compose logs frontend

# Testar configuração
docker compose config

# Reconstruir
docker compose down
docker compose build --no-cache
docker compose up -d
```

### Certificado não obtido
```bash
# 1. Verificar DNS
dig sindicofk.com.br

# 2. Verificar firewall
sudo ufw status

# 3. Verificar logs
docker compose logs certbot

# 4. Testar em modo staging
# Editar init-letsencrypt.sh: staging=1
./init-letsencrypt.sh
```

## 📞 Suporte

- Documentação completa: [CONFIGURACAO_HTTPS.md](CONFIGURACAO_HTTPS.md)
- Verificação técnica: [VERIFICACAO_HTTPS.md](VERIFICACAO_HTTPS.md)
- Teste SSL: https://www.ssllabs.com/ssltest/
