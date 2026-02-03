# Implementação de Envio de Formulário - Síndico FK

## 📧 Configuração de E-mails

Todos os e-mails de teste serão enviados para: **msvareira@gmail.com**

### Variáveis de Ambiente

Foi adicionada a seguinte variável no arquivo `.env`:

```env
ADMIN_NOTIFICATION_EMAIL=msvareira@gmail.com
```

## 🚀 Como Testar

### 1. Iniciar o Backend

Certifique-se de que o Docker está rodando e execute:

```bash
cd backend
docker-compose up -d
```

Ou se estiver usando o servidor PHP local:

```bash
cd backend
php artisan serve --host=0.0.0.0 --port=8080
```

### 2. Verificar Configurações de Email

O sistema está configurado para usar o Mailpit (interface de teste de e-mails):

- **Host:** mailpit
- **Port:** 1025
- **Interface Web:** http://localhost:8025

Para visualizar os e-mails enviados, acesse: http://localhost:8025

### 3. Testar o Envio de E-mail (Backend)

Execute o script de teste:

```bash
cd backend
php test-email.php
```

Este script irá:
- Criar um lead de teste
- Enviar e-mail de notificação para o administrador (msvareira@gmail.com)
- Enviar e-mail de confirmação para o lead
- Exibir as configurações de e-mail
- Limpar os dados de teste

### 4. Testar o Formulário (Frontend)

#### Opção A: Página de Teste
Abra o arquivo `test-form.html` no navegador:

```
file:///E:/Trabalho/Mauricio%20Corp/SindicoFK/Site/test-form.html
```

Ou use um servidor local:

```bash
# Na raiz do projeto
python -m http.server 3000
# Acesse: http://localhost:3000/test-form.html
```

#### Opção B: Formulário Real
Abra o arquivo `contato.html` no navegador ou através do servidor local.

### 5. Verificar E-mails Enviados

Acesse o Mailpit em http://localhost:8025 para ver todos os e-mails enviados.

## 📝 O Que Foi Implementado

### Backend

1. **Mail Classes** (`app/Mail/`)
   - `NewLeadNotification.php` - E-mail para o administrador
   - `LeadConfirmation.php` - E-mail de confirmação para o lead

2. **Email Templates** (`resources/views/emails/`)
   - `new-lead-notification.blade.php` - Template HTML para notificação
   - `lead-confirmation.blade.php` - Template HTML para confirmação

3. **Controller** (`app/Http/Controllers/Api/LeadController.php`)
   - Implementação de envio de e-mails no método `store()`
   - Tratamento de erros com logs
   - Validação de dados

4. **Configuração**
   - `config/cors.php` - Configuração CORS para permitir requisições do frontend
   - `config/mail.php` - Adicionado `admin_notification_email`
   - `bootstrap/app.php` - Middleware CORS habilitado

### Frontend

1. **JavaScript** (`js/script.js`)
   - Integração com API REST (`POST /api/leads`)
   - Validação de formulário
   - Feedback visual (loading, success, error)
   - Tratamento de erros

2. **HTML**
   - `test-form.html` - Página de teste com formulário e debug

## 🔧 Configuração de Produção

Para produção, altere as seguintes configurações:

### .env
```env
# Mudar de 'log' para 'smtp'
MAIL_MAILER=smtp

# Configurar servidor SMTP real
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=adm.sindicofk@gmail.com
MAIL_PASSWORD=sua_senha_ou_app_password
MAIL_ENCRYPTION=tls

# E-mail do administrador
ADMIN_NOTIFICATION_EMAIL=adm.sindicofk@gmail.com
```

### JavaScript (js/script.js)
Alterar a URL da API de desenvolvimento para produção:

```javascript
// Desenvolvimento
const response = await fetch('http://localhost:8080/api/leads', {

// Produção
const response = await fetch('https://api.sindicofk.com.br/api/leads', {
```

Ou use uma variável de ambiente/configuração:

```javascript
const API_URL = window.location.hostname === 'localhost' 
    ? 'http://localhost:8080/api' 
    : 'https://api.sindicofk.com.br/api';

const response = await fetch(`${API_URL}/leads`, {
```

## 📋 Checklist de Teste

- [ ] Backend está rodando (http://localhost:8080)
- [ ] Mailpit está acessível (http://localhost:8025)
- [ ] Teste de e-mail via PHP executado com sucesso
- [ ] Formulário de teste envia dados corretamente
- [ ] E-mail de notificação recebido no Mailpit
- [ ] E-mail de confirmação recebido no Mailpit
- [ ] Formulário real (contato.html) funcionando
- [ ] Mensagens de erro são exibidas corretamente
- [ ] Lead é criado no banco de dados

## 🐛 Troubleshooting

### Erro de CORS
Se aparecer erro de CORS no console do navegador:
- Verificar se o backend está rodando
- Conferir `CORS_ALLOWED_ORIGINS` no `.env`
- Verificar se o middleware CORS está ativo em `bootstrap/app.php`

### E-mail não enviado
- Verificar se o container do Mailpit está rodando: `docker-compose ps`
- Conferir logs: `docker-compose logs mailpit`
- Verificar configurações em `.env`

### Formulário não envia
- Abrir console do navegador (F12) e verificar erros
- Confirmar que a API está acessível: http://localhost:8080/api/health
- Verificar se o JavaScript está carregado corretamente

## 📚 Referências

- [Laravel Mail Documentation](https://laravel.com/docs/11.x/mail)
- [Mailpit - Email Testing](https://github.com/axllent/mailpit)
- [Laravel CORS](https://laravel.com/docs/11.x/routing#cors)
