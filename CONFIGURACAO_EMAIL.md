# Configuração de Email - Síndico FK

## ✅ Problema Resolvido

O sistema estava com configurações conflitantes entre:
- `docker-compose.yml` da raiz (forçando MAIL_HOST=mailpit)
- `.env` do backend (configurado para Gmail)

### Solução Implementada

1. **Removidas variáveis de email do docker-compose.yml**
   - Removido `MAIL_HOST` e `MAIL_PORT` das variáveis de ambiente
   - Agora o `.env` do backend controla totalmente as configurações de email

2. **Configurado Mailpit para testes locais**
   - Host: `sindicofk-mailpit`
   - Porta SMTP: `1025`
   - Interface Web: `http://localhost:8025`

## Configurações Atuais

### Para Ambiente de Desenvolvimento (Mailpit)

No arquivo `backend/.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sindicofk-mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="contato@sindicofk.com"
MAIL_FROM_NAME="${APP_NAME}"
ADMIN_NOTIFICATION_EMAIL=msvareira@gmail.com
```

**Vantagens:**
- ✅ Todos os emails são capturados localmente
- ✅ Não envia emails reais durante desenvolvimento
- ✅ Interface web para visualizar emails: http://localhost:8025
- ✅ Não precisa de credenciais reais

### Para Produção (Gmail)

Quando for colocar em produção, altere o `backend/.env` para:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=msvareira@gmail.com
MAIL_PASSWORD=mlyuahauejnomezs
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="msvareira@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
ADMIN_NOTIFICATION_EMAIL=msvareira@gmail.com
```

**Importante:**
- ⚠️ Use "Senhas de app" do Gmail, não a senha normal
- ⚠️ Ative a autenticação de 2 fatores no Gmail
- ⚠️ Gere uma senha específica em: https://myaccount.google.com/apppasswords

## Como Testar

### 1. Via Interface Web do Site
Acesse http://localhost:81/contato.html e preencha o formulário

### 2. Via API Diretamente
```powershell
cd backend
$body = Get-Content test-lead.json -Raw
Invoke-RestMethod -Uri "http://localhost:8080/api/leads" -Method POST -Body $body -ContentType "application/json"
```

### 3. Verificar Emails Recebidos
- Abra o Mailpit: http://localhost:8025
- Verifique a lista de emails recebidos
- Clique em um email para ver o conteúdo completo

## Tipos de Email Enviados

1. **Notificação para Administrador**
   - Enviado para: `ADMIN_NOTIFICATION_EMAIL`
   - Assunto: "🏢 Novo Lead - [Nome do Lead]"
   - Contém: Todos os dados do lead

2. **Confirmação para o Lead**
   - Enviado para: email do lead
   - Assunto: "Recebemos seu contato - Síndico FK"
   - Contém: Mensagem de confirmação e número de protocolo

## Logs

Para verificar se os emails estão sendo enviados:

```powershell
docker exec sindicofk-backend tail -f storage/logs/laravel.log
```

Procure por:
- `Email de notificação enviado para administrador`
- `Email de confirmação enviado para lead`

## Status Atual

✅ Backend funcionando corretamente
✅ Emails sendo enviados com sucesso
✅ Mailpit recebendo todos os emails
✅ Logs confirmando envios
✅ API respondendo corretamente

## Troubleshooting

### Emails não estão sendo enviados

1. Verifique se o Mailpit está rodando:
   ```powershell
   docker-compose ps mailpit
   ```

2. Limpe o cache do Laravel:
   ```powershell
   docker exec sindicofk-backend php artisan config:clear
   docker-compose restart backend
   ```

3. Verifique os logs:
   ```powershell
   docker-compose logs backend --tail=50
   ```

### Quero usar Gmail em desenvolvimento

Basta alterar o `backend/.env` com as configurações do Gmail e reiniciar:
```powershell
docker-compose restart backend
```
