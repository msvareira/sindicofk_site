# 📦 Guia de Deploy - Servidores Separados

Este guia mostra como fazer deploy do Frontend e Backend em servidores separados.

## 🎯 Cenário

- **Servidor Frontend:** 192.168.1.101 (ou seu IP/domínio)
- **Servidor Backend:** 192.168.1.100 (ou seu IP/domínio)

## 📋 Pré-requisitos

Ambos os servidores:
- ✅ Docker e Docker Compose instalados
- ✅ Git instalado
- ✅ Portas necessárias liberadas no firewall
- ✅ Acesso SSH configurado

---

## 🔧 Servidor Backend (192.168.1.100)

### 1. Clonar repositório

```bash
git clone <seu-repositorio>
cd SindicoFK/Site/backend
```

### 2. Configurar variáveis de ambiente

```bash
cp .env.example .env
nano .env
```

Ajustar:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=http://192.168.1.100:8080

DB_HOST=mysql
DB_DATABASE=sindicofk_crm
DB_USERNAME=sindicofk
DB_PASSWORD=SENHA_FORTE_AQUI

CORS_ALLOWED_ORIGINS=http://192.168.1.101,https://sindicofk.com.br
```

### 3. Configurar CORS

Edite `config/cors.php`:
```php
'allowed_origins' => [
    'http://192.168.1.101',
    'https://sindicofk.com.br',
],
```

### 4. Iniciar containers

```bash
docker-compose up -d
```

### 5. Configuração inicial

```bash
# Instalar dependências
docker-compose exec app composer install --optimize-autoloader --no-dev

# Gerar chave
docker-compose exec app php artisan key:generate

# Executar migrations
docker-compose exec app php artisan migrate --force

# Cache de configuração (produção)
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache
```

### 6. Configurar firewall

```bash
# Ubuntu/Debian
sudo ufw allow 8080/tcp
sudo ufw allow from 192.168.1.101 to any port 8080

# CentOS/RHEL
sudo firewall-cmd --permanent --add-port=8080/tcp
sudo firewall-cmd --permanent --add-rich-rule='rule family="ipv4" source address="192.168.1.101" port protocol="tcp" port="8080" accept'
sudo firewall-cmd --reload
```

### 7. Verificar

```bash
# Status dos containers
docker-compose ps

# Testar API
curl http://localhost:8080/api/health

# Ver logs
docker-compose logs -f
```

---

## 🌐 Servidor Frontend (192.168.1.101)

### 1. Clonar repositório

```bash
git clone <seu-repositorio>
cd SindicoFK/Site/frontend
```

### 2. Configurar URL da API

Edite `js/config.js`:
```javascript
getApiUrl() {
    if (this.isDevelopment) {
        return 'http://localhost:8080/api';
    }
    // URL do servidor backend
    return 'http://192.168.1.100:8080/api';
}
```

### 3. Criar arquivo .env (opcional)

```bash
echo "FRONTEND_PORT=80" > .env
echo "BACKEND_API_URL=http://192.168.1.100:8080/api" >> .env
```

### 4. Iniciar container

```bash
docker-compose up -d
```

### 5. Configurar firewall

```bash
# Ubuntu/Debian
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# CentOS/RHEL
sudo firewall-cmd --permanent --add-port=80/tcp
sudo firewall-cmd --permanent --add-port=443/tcp
sudo firewall-cmd --reload
```

### 6. Verificar

```bash
# Status do container
docker-compose ps

# Testar
curl http://localhost/

# Testar comunicação com backend
curl http://192.168.1.100:8080/api/health
```

---

## ✅ Checklist de Verificação

### Backend (192.168.1.100)

- [ ] Container rodando: `docker-compose ps`
- [ ] API responde: `curl http://localhost:8080/api/health`
- [ ] MySQL conectado: `docker-compose exec app php artisan migrate:status`
- [ ] Firewall permite porta 8080
- [ ] CORS configurado para aceitar frontend
- [ ] .env configurado corretamente
- [ ] Logs sem erros: `docker-compose logs`

### Frontend (192.168.1.101)

- [ ] Container rodando: `docker-compose ps`
- [ ] Site carrega: `curl http://localhost/`
- [ ] js/config.js com URL correta do backend
- [ ] Firewall permite portas 80/443
- [ ] Consegue acessar backend: `curl http://192.168.1.100:8080/api/health`

### Integração

- [ ] Frontend carrega no navegador
- [ ] Console do navegador (F12) sem erros de CORS
- [ ] Formulário de contato envia com sucesso
- [ ] Backend recebe requisições do frontend

---

## 🔒 SSL/HTTPS (Recomendado para Produção)

### Opção 1: Certbot (Let's Encrypt) - Gratuito

**Frontend:**
```bash
# Instalar Certbot
sudo apt-get update
sudo apt-get install certbot

# Parar Nginx temporariamente
docker-compose down

# Obter certificado
sudo certbot certonly --standalone -d sindicofk.com.br

# Certificados em: /etc/letsencrypt/live/sindicofk.com.br/
```

Criar `docker-compose.prod.yml` com SSL:
```yaml
version: '3.8'
services:
  frontend:
    build: .
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - /etc/letsencrypt:/etc/letsencrypt:ro
      - ./nginx-ssl.conf:/etc/nginx/conf.d/default.conf
```

### Opção 2: Reverse Proxy (Traefik/Nginx Proxy Manager)

Use um proxy reverso na frente para gerenciar SSL automaticamente.

---

## 🔄 Atualizações

### Backend

```bash
cd backend/
git pull
docker-compose down
docker-compose build --no-cache
docker-compose up -d
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan config:cache
```

### Frontend

```bash
cd frontend/
git pull
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

---

## 🆘 Troubleshooting

### Erro de CORS

**Sintoma:** Console do navegador mostra erro de CORS

**Solução:**
1. Verificar `backend/config/cors.php`
2. Adicionar IP/domínio do frontend em `allowed_origins`
3. Reiniciar backend: `docker-compose restart`

### Frontend não conecta no Backend

**Verificações:**
```bash
# Do servidor frontend, testar backend
curl http://192.168.1.100:8080/api/health

# Verificar firewall do backend
sudo ufw status
```

### Containers não iniciam

```bash
# Ver logs
docker-compose logs

# Verificar portas
sudo netstat -tulpn | grep -E ':(80|8080|3306)'

# Limpar e reiniciar
docker-compose down -v
docker-compose up -d --build
```

---

## 📊 Monitoramento

### Logs em tempo real

**Backend:**
```bash
docker-compose logs -f app
docker-compose exec app tail -f storage/logs/laravel.log
```

**Frontend:**
```bash
docker-compose logs -f frontend
docker-compose exec frontend tail -f /var/log/nginx/access.log
```

### Status dos serviços

```bash
docker-compose ps
docker stats
```

---

## 🎯 Próximos Passos

1. **Backup automático:**
   - Configure cron jobs para backup do banco de dados
   - Backup dos volumes Docker

2. **Monitoramento:**
   - Instale ferramentas como Prometheus/Grafana
   - Configure alertas

3. **CI/CD:**
   - Configure GitHub Actions ou GitLab CI
   - Deploy automático em push

4. **CDN:**
   - Use CloudFlare na frente do frontend
   - Cache de assets estáticos

---

## 📞 Suporte

- E-mail: adm.sindicofk@gmail.com
- WhatsApp: (51) 98269-5830

---

**Última atualização:** Janeiro 2026
