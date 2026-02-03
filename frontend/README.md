# Frontend - Síndico FK

Site institucional da Síndico FK usando Nginx para servir arquivos estáticos.

## 🐳 Docker - Desenvolvimento Local

### Iniciar o frontend

```bash
docker-compose up -d
```

### Acessar

- Site: http://localhost

## 🚀 Produção - Deploy em Servidor Separado

### Cenário 1: Frontend e Backend na mesma rede local

**Pré-requisitos:**
- Backend rodando em `http://192.168.1.100:8080` (exemplo)
- Frontend será acessado em `http://192.168.1.101` (exemplo)

**1. Configure a URL da API:**

Edite `js/config.js`:
```javascript
getApiUrl() {
    if (this.isDevelopment) {
        return 'http://localhost:8080/api';
    }
    // Produção - ajuste para o IP/domínio do backend
    return 'http://192.168.1.100:8080/api';
}
```

**2. Build e deploy:**
```bash
docker-compose up -d
```

### Cenário 2: Frontend e Backend com domínios públicos

**Backend:** `https://api.sindicofk.com.br`  
**Frontend:** `https://sindicofk.com.br`

**1. Configure a URL da API:**

Edite `js/config.js`:
```javascript
getApiUrl() {
    if (this.isDevelopment) {
        return 'http://localhost:8080/api';
    }
    return 'https://api.sindicofk.com.br/api';
}
```

**2. Configure SSL (recomendado):**

Crie `docker-compose.prod.yml`:

```yaml
version: '3.8'

services:
  frontend:
    build:
      context: .
      dockerfile: Dockerfile
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./ssl:/etc/nginx/ssl  # Certificados SSL
      - ./nginx-ssl.conf:/etc/nginx/conf.d/default.conf
    restart: always
```

**3. Configuração Nginx com SSL:**

Crie `nginx-ssl.conf`:
```nginx
server {
    listen 80;
    server_name sindicofk.com.br;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name sindicofk.com.br;

    ssl_certificate /etc/nginx/ssl/cert.pem;
    ssl_certificate_key /etc/nginx/ssl/key.pem;

    root /usr/share/nginx/html;
    index index.html;

    location / {
        try_files $uri $uri/ /index.html;
    }

    # Cache para assets
    location ~* \.(css|js|jpg|jpeg|png|gif|ico|svg)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }
}
```

## 🌐 Variáveis de Ambiente

Configure no `.env` ou `docker-compose.yml`:

| Variável | Desenvolvimento | Produção |
|----------|----------------|-----------|
| FRONTEND_PORT | 80 | 80 ou 443 |
| BACKEND_API_URL | http://localhost:8080/api | URL do backend |

## 📋 Estrutura de Arquivos

```
frontend/
├── css/              # Estilos
├── js/
│   ├── config.js    # Configurações (URLs da API)
│   └── script.js    # Lógica principal
├── imagens/         # Assets
├── *.html           # Páginas
├── Dockerfile       # Container
├── nginx.conf       # Configuração Nginx
└── docker-compose.yml
```

## 🔧 Configuração de Rede para Servidores Separados

### Frontend (192.168.1.101) → Backend (192.168.1.100)

**1. Backend - Configure CORS:**

No servidor backend, edite `backend/config/cors.php`:
```php
'allowed_origins' => [
    'http://192.168.1.101',
    'https://sindicofk.com.br',
],
```

**2. Frontend - Configure URL da API:**

Edite `js/config.js`:
```javascript
getApiUrl() {
    return 'http://192.168.1.100:8080/api';
}
```

**3. Firewall:**

No servidor backend, permita acesso do frontend:
```bash
# Ubuntu/Debian
sudo ufw allow from 192.168.1.101 to any port 8080

# CentOS/RHEL
sudo firewall-cmd --permanent --add-rich-rule='rule family="ipv4" source address="192.168.1.101" port protocol="tcp" port="8080" accept'
sudo firewall-cmd --reload
```

## 📦 Deployment

### Opção 1: Docker Compose

```bash
# Build e start
docker-compose up -d --build

# Ver logs
docker-compose logs -f

# Parar
docker-compose down
```

### Opção 2: Apenas Nginx (sem Docker)

Se preferir não usar Docker:

```bash
# Copiar arquivos para servidor web
sudo cp -r * /var/www/html/

# Configurar Nginx
sudo nano /etc/nginx/sites-available/sindicofk
# Cole a configuração do nginx.conf

# Ativar site
sudo ln -s /etc/nginx/sites-available/sindicofk /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Opção 3: CDN / Hosting estático

Pode hospedar em:
- Netlify
- Vercel
- GitHub Pages
- AWS S3 + CloudFront
- Azure Static Web Apps

Apenas ajuste a URL da API em `js/config.js`.

## 🔄 Atualizações

```bash
# Pull do código
git pull

# Rebuild
docker-compose down
docker-compose up -d --build
```

## 🔒 Segurança - Produção

1. **HTTPS obrigatório:**
   - Use Let's Encrypt para certificados gratuitos
   - Configure redirect HTTP → HTTPS

2. **Headers de segurança:**
   - X-Frame-Options
   - X-Content-Type-Options
   - Content-Security-Policy

3. **Firewall:**
   - Abrir apenas portas 80 e 443
   - Bloquear acesso direto a arquivos sensíveis

## 🚦 Health Check

### Verificar se frontend está respondendo

```bash
curl http://localhost/
```

### Verificar se consegue acessar backend

```bash
# Do servidor frontend
curl http://192.168.1.100:8080/api/health
```

## 📊 Monitoramento

### Logs do Nginx

```bash
docker-compose logs -f frontend
```

### Logs de acesso

```bash
docker-compose exec frontend tail -f /var/log/nginx/access.log
```

### Logs de erro

```bash
docker-compose exec frontend tail -f /var/log/nginx/error.log
```

## 🆘 Troubleshooting

### CSS/JS não carregam

Verifique os logs:
```bash
docker-compose logs frontend
```

Verifique permissões:
```bash
ls -la css/ js/ imagens/
```

### Erro de CORS ao chamar API

1. Verifique se backend tem CORS configurado
2. Confirme se URL da API está correta em `js/config.js`
3. Teste a API diretamente: `curl http://backend:8080/api/health`

### Formulário não envia

1. Abra o console do navegador (F12)
2. Vá para a aba Network
3. Envie o formulário e veja se há erros

## 🔄 Integração Contínua

### GitHub Actions (exemplo)

```yaml
name: Deploy Frontend

on:
  push:
    branches: [ main ]
    paths:
      - 'frontend/**'

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      
      - name: Deploy to server
        uses: easingthemes/ssh-deploy@v2
        with:
          SSH_PRIVATE_KEY: ${{ secrets.SSH_PRIVATE_KEY }}
          REMOTE_HOST: ${{ secrets.REMOTE_HOST }}
          REMOTE_USER: ${{ secrets.REMOTE_USER }}
          SOURCE: "frontend/"
          TARGET: "/var/www/sindicofk"
```

## 📞 Suporte

- E-mail: adm.sindicofk@gmail.com
- WhatsApp: (51) 98269-5830

---

**Nota:** Este é um site estático (HTML/CSS/JS) que faz chamadas assíncronas para o backend via JavaScript. Não requer processamento server-side.
