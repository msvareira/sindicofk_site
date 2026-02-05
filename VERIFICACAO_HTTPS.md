# Verificação de Configuração HTTPS - Síndico FK

## ✅ Configurações Aplicadas

### 1. **Frontend - JavaScript (config.js)**
- ✅ Em **produção**: Usa `window.location.origin + '/api'`
- ✅ Isso garante: `https://sindicofk.com.br/api`
- ✅ Em **desenvolvimento**: Mantém `http://localhost:8080/api`

### 2. **Frontend - Nginx (nginx.conf)**
- ✅ Porta 80 → Redireciona para HTTPS
- ✅ Porta 443 → Servidor HTTPS ativo
- ✅ Location `/api` → Proxy reverso para `backend:80`
- ✅ Headers de proxy configurados (X-Forwarded-Proto, etc.)

### 3. **Backend - Laravel (.env)**
- ✅ `APP_URL=https://sindicofk.com.br`
- ✅ `CORS_ALLOWED_ORIGINS` inclui domínios HTTPS
- ✅ `SITE_URL=https://sindicofk.com.br`

### 4. **Backend - AppServiceProvider.php**
- ✅ `URL::forceScheme('https')` em produção
- ✅ Força Laravel a gerar URLs HTTPS

### 5. **Docker Compose**
- ✅ Frontend expõe portas 80 e 443
- ✅ Backend roda internamente na porta 80 (não exposto diretamente)
- ✅ Certbot configurado para renovação automática

## 🔄 Fluxo de Requisições

```
Cliente (Browser)
    ↓
https://sindicofk.com.br/contato.html
    ↓
Frontend Nginx (Container) - Porta 443
    ↓
JavaScript: SindicoFKConfig.getApiUrl()
    → Retorna: "https://sindicofk.com.br/api"
    ↓
Requisição AJAX para: https://sindicofk.com.br/api/leads
    ↓
Nginx Proxy (location /api)
    → proxy_pass → http://backend:80/api
    ↓
Backend Laravel (Container)
    → Processa a requisição
    → Responde com JSON
    ↓
Nginx encaminha resposta
    ↓
Cliente recebe resposta (HTTPS)
```

## 🔐 Segurança Garantida

### Sem Mixed Content
- ✅ Frontend HTTPS carrega API via HTTPS
- ✅ Proxy interno usa HTTP (seguro dentro da rede Docker)
- ✅ Navegador não bloqueia requisições

### CORS Configurado
- ✅ `https://sindicofk.com.br` permitido
- ✅ `https://www.sindicofk.com.br` permitido
- ✅ Headers apropriados configurados

### Headers de Segurança
- ✅ X-Frame-Options: SAMEORIGIN
- ✅ X-Content-Type-Options: nosniff
- ✅ X-XSS-Protection: 1; mode=block
- ✅ SSL/TLS 1.2 e 1.3 apenas

## 📊 Testes de Verificação

### 1. Testar Redirecionamento HTTP → HTTPS
```powershell
# Deve redirecionar para HTTPS
curl -I http://sindicofk.com.br
```
**Esperado:** `HTTP/1.1 301 Moved Permanently` + `Location: https://...`

### 2. Testar HTTPS
```powershell
# Deve retornar 200 OK
curl -I https://sindicofk.com.br
```
**Esperado:** `HTTP/2 200`

### 3. Testar API via HTTPS
```powershell
# Teste de ping/health da API
curl https://sindicofk.com.br/api/health -k
```

### 4. Verificar Certificado SSL
```powershell
# Ver detalhes do certificado
openssl s_client -connect sindicofk.com.br:443 -servername sindicofk.com.br
```

### 5. Testar Formulário
1. Acesse: `https://sindicofk.com.br/contato.html`
2. Preencha o formulário
3. Envie
4. Verifique no console do navegador (F12):
   - ✅ Requisição para `https://sindicofk.com.br/api/leads`
   - ✅ Status 200/201
   - ✅ Sem erros de Mixed Content
   - ✅ Sem erros de CORS

## 🐛 Troubleshooting

### Mixed Content Error
```
Mixed Content: The page at 'https://...' was loaded over HTTPS, 
but requested an insecure resource 'http://...'
```

**Solução:** Já corrigido! `config.js` usa `window.location.origin`

### CORS Error
```
Access to XMLHttpRequest at 'https://sindicofk.com.br/api/leads' 
from origin 'https://sindicofk.com.br' has been blocked by CORS policy
```

**Solução:** Já corrigido! `.env` tem `CORS_ALLOWED_ORIGINS` configurado

### 502 Bad Gateway na API
```
nginx: [error] connect() failed (111: Connection refused)
```

**Causas possíveis:**
- Backend não está rodando
- Nome do serviço errado no proxy_pass

**Verificação:**
```powershell
# Ver se backend está rodando
docker-compose ps

# Ver logs do backend
docker-compose logs backend

# Ver logs do nginx
docker-compose logs frontend
```

### Certificado Inválido
**Causas:**
- Certificados ainda não foram gerados
- Let's Encrypt falhou

**Solução:**
```powershell
# Executar script de inicialização
.\init-letsencrypt.ps1

# Ver logs do certbot
docker-compose logs certbot
```

## 📝 Checklist Pré-Deploy

Antes de executar `.\init-letsencrypt.ps1`:

- [ ] DNS configurado (sindicofk.com.br → IP do servidor)
- [ ] DNS propagado (verificar com `nslookup`)
- [ ] Portas 80 e 443 abertas no firewall
- [ ] Docker rodando
- [ ] Containers parados (`docker-compose down`)
- [ ] Domínio e email corretos no script

## 🚀 Ordem de Execução

1. **Parar tudo:**
```powershell
docker-compose down
```

2. **Executar certificação SSL:**
```powershell
.\init-letsencrypt.ps1
```

3. **Verificar status:**
```powershell
docker-compose ps
docker-compose logs frontend
docker-compose logs certbot
```

4. **Testar site:**
- Abrir: https://sindicofk.com.br
- Testar formulário de contato
- Verificar console do navegador (F12)

## 📌 Pontos Importantes

### Desenvolvimento Local
- Frontend: `http://localhost:81`
- Backend API: `http://localhost:8080/api`
- **Sem HTTPS** (apenas HTTP)

### Produção (Servidor)
- Frontend: `https://sindicofk.com.br`
- Backend API: `https://sindicofk.com.br/api`
- **Com HTTPS** (Nginx proxy reverso)
- Porta 8080 **NÃO** exposta externamente

### Arquivo .env do Backend

**Importante:** O arquivo `.env` foi atualizado para:
```
APP_ENV=production
APP_DEBUG=false
```

Se precisar fazer debug em produção temporariamente:
```
APP_DEBUG=true
```

Mas **NUNCA** deixe `APP_DEBUG=true` em produção por segurança!

## ✨ Resultado Final

Após a configuração:

1. ✅ `http://sindicofk.com.br` → Redireciona para HTTPS
2. ✅ `https://sindicofk.com.br` → Site carrega com certificado válido
3. ✅ Formulários funcionam via HTTPS
4. ✅ API responde via proxy reverso
5. ✅ Sem erros de Mixed Content
6. ✅ Sem erros de CORS
7. ✅ Certificado renova automaticamente

## 🔗 Links Úteis

- [SSL Labs Test](https://www.ssllabs.com/ssltest/)
- [Let's Encrypt Status](https://letsencrypt.status.io/)
- [Mozilla SSL Config Generator](https://ssl-config.mozilla.org/)
