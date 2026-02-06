# 🚀 Guia Rápido: Desenvolvimento Local vs Produção

## 🏠 Desenvolvimento Local (HTTP - Sem SSL)

Para rodar o site localmente **sem HTTPS** (desenvolvimento):

### Opção 1: Usando o Script Automático (Recomendado)

```bash
# Windows
.\start-local.bat

# Ou manualmente via PowerShell
.\start-local.bat
```

### Opção 2: Comandos Manuais

```bash
# 1. Copiar configuração local
copy frontend\nginx-local.conf frontend\nginx-temp.conf

# 2. Reiniciar containers
docker-compose down
docker-compose up -d --build
```

### Acessar Localmente:
- 🌐 **Frontend:** http://localhost:81
- 🔧 **Backend API:** http://localhost:8080/api
- 📧 **Mailpit:** http://localhost:8025
- 🗄️ **MySQL:** localhost:3306

---

## 🌍 Produção (HTTPS - VPS)

Para rodar no VPS **com HTTPS**:

### Opção 1: Usando o Script Automático (Recomendado)

```bash
# No VPS (Linux)
chmod +x deploy-vps.sh
./deploy-vps.sh
```

### Opção 2: Comandos Manuais

```bash
# No VPS:

# 1. Garantir que não existe nginx-temp.conf
rm -f frontend/nginx-temp.conf

# 2. Reiniciar containers
docker-compose down
docker-compose up -d --build

# 3. Verificar logs
docker-compose logs -f frontend
```

### Acessar Produção:
- 🌐 **Frontend:** https://sindicofk.com.br
- 🔧 **Backend API:** https://sindicofk.com.br/api

---

## 📁 Arquivos de Configuração

| Arquivo | Uso | Descrição |
|---------|-----|-----------|
| `nginx.conf` | Produção | Configuração com HTTPS e SSL |
| `nginx-local.conf` | Desenvolvimento | Configuração HTTP simples |
| `nginx-temp.conf` | Temporário | Criado automaticamente pelos scripts |

---

## 🔄 Alternando entre Ambientes

### Local → Produção
```bash
.\start-production.bat
```

### Produção → Local
```bash
.\start-local.bat
```

---

## 🐛 Resolução de Problemas

### Frontend não carrega (erro de certificado)
**Causa:** Usando configuração HTTPS localmente  
**Solução:** Execute `.\start-local.bat`

### API não responde
**Causa:** Backend não iniciado  
**Solução:** 
```bash
docker-compose logs backend
docker-compose restart backend
```

### MySQL não conecta
**Causa:** Banco não iniciado ou credenciais erradas  
**Solução:**
```bash
docker-compose logs mysql
docker-compose restart mysql
```

### Porta já em uso
**Causa:** Outro serviço usando a porta  
**Solução:**
```bash
# Windows - Ver processo na porta
netstat -ano | findstr :81
# Matar processo (substitua PID)
taskkill /PID <PID> /F
```

---

## 📊 Comandos Úteis

```bash
# Ver logs em tempo real
docker-compose logs -f

# Ver logs de um serviço específico
docker-compose logs -f frontend
docker-compose logs -f backend

# Parar todos os containers
docker-compose down

# Rebuild completo (sem cache)
docker-compose build --no-cache
docker-compose up -d

# Entrar no container
docker exec -it sindicofk-frontend sh
docker exec -it sindicofk-backend bash

# Ver status dos containers
docker-compose ps

# Limpar tudo (CUIDADO: apaga volumes)
docker-compose down -v
```

---

## ✅ Checklist de Verificação

### Desenvolvimento Local
- [ ] `nginx-temp.conf` existe e está usando configuração local
- [ ] Containers rodando sem erros
- [ ] http://localhost:81 acessível
- [ ] API responde em http://localhost:8080/api
- [ ] Mailpit acessível em http://localhost:8025

### Produção
- [ ] `nginx-temp.conf` existe e está usando configuração HTTPS
- [ ] Certificados SSL válidos
- [ ] https://sindicofk.com.br acessível
- [ ] API responde em https://sindicofk.com.br/api
- [ ] Redirecionamento HTTP → HTTPS funcionando

---

## 🎯 Fluxo de Trabalho Recomendado

```
1. Desenvolvimento Local
   ├── start-local.bat
   ├── Editar código em /frontend ou /backend
   ├── docker-compose restart (para aplicar mudanças)
   └── Testar em http://localhost:81

2. Antes de Deploy
   ├── Testar tudo localmente
   ├── Commitar mudanças no Git
   └── git push

3. Deploy em Produção (VPS)
   ├── git pull (no VPS)
   ├── start-production.bat
   └── Verificar https://sindicofk.com.br
```

---

## 📝 Notas Importantes

⚠️ **Nunca commite** `nginx-temp.conf` no Git (arquivo temporário)  
⚠️ **No VPS**: Use `deploy-vps.sh` após `git pull`  
⚠️ **Localmente**: Use `start-local.bat` para desenvolvimento  
⚠️ **Verifique** qual configuração está ativa antes de fazer deploy  
✅ **Teste** localmente antes de fazer push para produção

### 🚨 Se o Site Parar no VPS

Se após fazer deploy o site não funcionar no VPS:

```bash
# No VPS, execute:
rm -f frontend/nginx-temp.conf
docker-compose down
docker-compose up -d --build
```

Ou use o script: `./deploy-vps.sh`

Consulte [SOLUCAO-VPS.md](SOLUCAO-VPS.md) para troubleshooting completo.

---

**Criado em:** 06/02/2026  
**Última atualização:** 06/02/2026
