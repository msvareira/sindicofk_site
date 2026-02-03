# 🏢 Síndico FK - Sistema Completo

Sistema de gestão e site institucional da Síndico FK com arquitetura moderna usando Docker.

## 📁 Estrutura do Projeto

```
SindicoFK/Site/
├── frontend/                    # Frontend (HTML, CSS, JS)
│   ├── css/                    # Estilos
│   ├── js/                     # JavaScript
│   │   ├── config.js          # Configurações da aplicação
│   │   └── script.js          # Lógica principal
│   ├── imagens/               # Imagens e assets
│   ├── *.html                 # Páginas HTML
│   ├── Dockerfile             # Container Nginx
│   ├── nginx.conf             # Configuração Nginx
│   ├── docker-compose.yml     # Docker frontend independente
│   └── README.md              # Documentação frontend
│
├── backend/                    # Backend (Laravel)
│   ├── app/                   # Código da aplicação
│   ├── config/                # Configurações
│   ├── database/              # Migrations e seeders
│   ├── routes/                # Rotas da API
│   ├── docker-compose.yml     # Docker backend independente
│   ├── Dockerfile             # Container PHP
│   └── README.md              # Documentação backend
│
├── docker-compose.yml          # Orquestração completa (dev local)
├── start.bat                  # Script inicialização (Windows)
├── stop.bat                   # Script parar (Windows)
└── README.md                  # Este arquivo
```

## 🐳 Arquitetura Docker

### Desenvolvimento Local (Tudo Integrado)

Use o `docker-compose.yml` na raiz para rodar tudo junto:

```powershell
# Iniciar tudo
docker-compose up -d

# Parar tudo
docker-compose down
```

**5 containers** serão iniciados:
1. **frontend** - Nginx (porta 81)
2. **backend** - PHP/Laravel (porta 8080)
3. **mysql** - MySQL 8.0 (porta 3306)
4. **redis** - Redis (porta 6379)
5. **mailpit** - E-mails teste (porta 8025)

### Produção (Ambientes Separados)

Cada ambiente tem seu próprio `docker-compose.yml` e pode rodar em máquinas separadas.

#### Backend (Máquina A - ex: 192.168.1.100)

```bash
cd backend/
docker-compose up -d
```

Inicia: Nginx + PHP-FPM + MySQL + Redis + Mailpit

#### Frontend (Máquina B - ex: 192.168.1.101)

```bash
cd frontend/
docker-compose up -d
```

Inicia: Nginx servindo arquivos estáticos

**Configure a comunicação:**
- Frontend: Edite `frontend/js/config.js` com IP do backend
- Backend: Configure CORS em `backend/config/cors.php`

## 🚀 Início Rápido

### Desenvolvimento Local (Tudo Junto)

**Pré-requisitos:**
- Docker Desktop instalado
- Portas liberadas: 81, 8080, 3306, 6379, 8025

**Primeira vez:**

```powershell
# 1. Iniciar todos os serviços
docker-compose up -d

# 2. Configurar backend
docker-compose exec backend bash
composer install
php artisan key:generate
php artisan migrate
exit

# 3. Acessar
# Site: http://localhost:81
# API: http://localhost:8080
# Mailpit: http://localhost:8025
```

**Uso diário:**
```powershell
# Iniciar (ou use start.bat)
docker-compose up -d

# Parar (ou use stop.bat)
docker-compose down
```

### Produção (Servidores Separados)

#### Cenário: Backend e Frontend em máquinas diferentes

**Servidor 1 - Backend (192.168.1.100):**

```bash
cd backend/

# Ajustar .env conforme necessário
cp .env.example .env
nano .env

# Iniciar serviços
docker-compose up -d

# Configuração inicial
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
```

**Servidor 2 - Frontend (192.168.1.101):**

```bash
cd frontend/

# Configurar URL do backend
nano js/config.js
# Altere: return 'http://192.168.1.100:8080/api';

# Iniciar serviço
docker-compose up -d
```

**Configurar CORS no Backend:**

No servidor backend, edite `backend/config/cors.php`:
```php
'allowed_origins' => ['http://192.168.1.101', 'https://sindicofk.com.br'],
```

### Produção Simplificada (Mesma Máquina)

Se quiser rodar tudo na mesma máquina mas com Docker Compose separados:

```bash
# Backend
cd backend && docker-compose up -d

# Frontend  
cd ../frontend && docker-compose up -d
```

## 📖 Documentação Detalhada

- **[Backend README](backend/README.md)** - Configuração, deploy e troubleshooting do backend
- **[Frontend README](frontend/README.md)** - Configuração, deploy e troubleshooting do frontend

## 📋 Comandos Úteis

### Gerenciar containers

```powershell
# Iniciar todos os serviços
docker-compose up -d

# Parar todos os serviços
docker-compose down

# Ver logs
docker-compose logs -f

# Ver logs de um serviço específico
docker-compose logs -f backend

# Reiniciar um serviço
docker-compose restart backend

# Reconstruir containers
docker-compose build

# Reconstruir e iniciar
docker-compose up -d --build
```

### Backend (Laravel)

```powershell
# Executar comandos no backend
docker-compose exec backend php artisan migrate
docker-compose exec backend php artisan db:seed
docker-compose exec backend php artisan cache:clear
docker-compose exec backend php artisan config:clear

# Acessar bash do backend
docker-compose exec backend bash

# Ver logs do Laravel
docker-compose exec backend tail -f storage/logs/laravel.log
```

### Banco de Dados

```powershell
# Acessar MySQL
docker-compose exec mysql mysql -usindicofk -psindicofk_secret sindicofk_crm

# Backup do banco
docker-compose exec mysql mysqldump -usindicofk -psindicofk_secret sindicofk_crm > backup.sql

# Restaurar banco
docker-compose exec -T mysql mysql -usindicofk -psindicofk_secret sindicofk_crm < backup.sql
```

### Redis

```powershell
# Acessar Redis CLI
docker-compose exec redis redis-cli

# Limpar cache
docker-compose exec redis redis-cli FLUSHALL
```

## 🌐 Ambientes

### Desenvolvimento Local

**Opção 1: Integrado (docker-compose.yml na raiz)**
- Tudo em um só comando
- Frontend: http://localhost:81
- Backend: http://localhost:8080

**Opção 2: Separado (cada ambiente com seu docker-compose.yml)**
- Backend: `cd backend && docker-compose up -d`
- Frontend: `cd frontend && docker-compose up -d`

### Produção

**Arquitetura Recomendada:**

```
┌─────────────────┐         ┌─────────────────┐
│  Servidor 1     │         │  Servidor 2     │
│  Frontend       │ ──────> │  Backend        │
│  (Nginx)        │  API    │  (Laravel)      │
│  192.168.1.101  │  HTTP   │  192.168.1.100  │
│  Porta 80/443   │         │  Porta 8080     │
└─────────────────┘         └─────────────────┘
                                    │
                            ┌───────┴────────┐
                            │                │
                       ┌────▼─────┐   ┌─────▼────┐
                       │  MySQL   │   │  Redis   │
                       └──────────┘   └──────────┘
```

**Configurações necessárias:**

1. **Frontend → Backend:**
   - Edite `frontend/js/config.js`
   - Configure URL do backend

2. **Backend → Frontend (CORS):**
   - Edite `backend/config/cors.php`
   - Adicione domínio/IP do frontend

3. **Firewall:**
   - Backend: Abrir porta 8080 para frontend
   - Frontend: Abrir portas 80/443 para internet

### Deploy Individual

Cada ambiente pode ser deployed separadamente:

**Backend:**
```bash
cd backend/
docker-compose up -d
```

**Frontend:**
```bash
cd frontend/
docker-compose up -d
```

## 🧪 Testando o Sistema

### 1. Testar API

```powershell
# Health check
curl http://localhost:8080/api/health

# Criar um lead (teste)
curl -X POST http://localhost:8080/api/leads `
  -H "Content-Type: application/json" `
  -d '{\"nome\":\"Teste\",\"email\":\"teste@example.com\",\"telefone\":\"51999999999\",\"mensagem\":\"Mensagem de teste\"}'
```

### 2. Testar Formulário

Acesse http://localhost/contato.html e preencha o formulário.

### 3. Verificar E-mails

Acesse http://localhost:8025 para ver os e-mails enviados pelo sistema.

## 📧 Configuração de E-mails

### Desenvolvimento
Por padrão, usa Mailpit (servidor de e-mail local para testes).

### Produção
Configure no `backend/.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu_email@gmail.com
MAIL_PASSWORD=sua_senha_app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=adm.sindicofk@gmail.com
ADMIN_NOTIFICATION_EMAIL=adm.sindicofk@gmail.com
```

## 🔧 Troubleshooting

### Portas já em uso

Se alguma porta estiver em uso:

```powershell
# Verificar o que está usando a porta
netstat -ano | findstr :80
netstat -ano | findstr :8080

# Matar processo (substitua PID)
taskkill /PID <PID> /F
```

Ou altere as portas no `docker-compose.yml`:

```yaml
ports:
  - "8081:80"  # Mudar frontend para porta 8081
```

### Erro de permissão (Linux/Mac)

```bash
# Dar permissão nas pastas do backend
sudo chown -R $USER:$USER backend/storage backend/bootstrap/cache
chmod -R 775 backend/storage backend/bootstrap/cache
```

### Containers não iniciam

```powershell
# Verificar logs
docker-compose logs

# Limpar tudo e recomeçar
docker-compose down -v
docker-compose up -d --build
```

### Erro ao conectar no MySQL

Aguarde alguns segundos após iniciar os containers. O MySQL pode levar um tempo para inicializar na primeira vez.

```powershell
# Verificar se MySQL está pronto
docker-compose exec mysql mysqladmin ping -h localhost
```

### Frontend não carrega arquivos CSS/JS

Verifique os logs do Nginx:

```powershell
docker-compose logs frontend
```

### Erro de CORS

Verifique se:
1. `backend/config/cors.php` existe
2. `CORS_ALLOWED_ORIGINS` está configurado no `.env`
3. Backend está rodando corretamente

## 📦 Volumes e Persistência

Os dados são persistidos em volumes Docker:

- `mysql-data` - Dados do banco de dados
- `redis-data` - Cache Redis
- `./backend` - Código do backend (montado como volume)

Para limpar todos os dados:

```powershell
docker-compose down -v  # -v remove volumes
```

## 🔒 Segurança

### Desenvolvimento
- Portas expostas para facilitar debug
- DEBUG mode ativado
- Senhas simples

### Produção
- Use variáveis de ambiente seguras
- Ative HTTPS/SSL
- Não exponha portas desnecessárias
- Use senhas fortes
- Configure firewall
- Desative APP_DEBUG

## 📞 Suporte

Para questões técnicas ou problemas:
- E-mail: adm.sindicofk@gmail.com
- WhatsApp: (51) 98269-5830

## 🔄 Atualizações

### Atualizar código

```powershell
# Git pull
git pull origin main

# Reconstruir e reiniciar
docker-compose up -d --build

# Executar migrations
docker-compose exec backend php artisan migrate
```

## 📝 Notas de Desenvolvimento

- O frontend detecta automaticamente se está em desenvolvimento ou produção
- Em desenvolvimento, a API aponta para `http://localhost:8080`
- Logs do Laravel ficam em `backend/storage/logs/`
- Cache é limpo automaticamente em desenvolvimento
- E-mails são capturados pelo Mailpit em desenvolvimento

## 🎯 Próximos Passos

- [ ] Configurar CI/CD
- [ ] Adicionar testes automatizados
- [ ] Implementar autenticação JWT
- [ ] Criar dashboard administrativo
- [ ] Configurar backup automático
- [ ] Adicionar monitoramento (opcional)
- [ ] Configurar SSL/HTTPS para produção

---

**Versão:** 1.0.0  
**Última atualização:** Janeiro 2026
