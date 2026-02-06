# 🚨 SOLUÇÃO: Site Não Funciona no VPS Após Ajustes Locais

## ❌ Problema

Após os ajustes para desenvolvimento local, o site parou de funcionar no VPS.

**Causa:** O arquivo `nginx-temp.conf` (usado para desenvolvimento local) pode ter sido commitado ou o VPS está tentando usar a configuração errada.

---

## ✅ Solução Rápida (No VPS)

### Opção 1: Usando o Script Automático

```bash
# No VPS, execute:
chmod +x deploy-vps.sh
./deploy-vps.sh
```

### Opção 2: Comandos Manuais

```bash
# 1. Garantir que não existe nginx-temp.conf
rm -f frontend/nginx-temp.conf

# 2. Rebuild dos containers
docker-compose down
docker-compose up -d --build

# 3. Verificar logs
docker-compose logs -f frontend
```

---

## 🔍 Verificar se Está Funcionando

```bash
# 1. Verificar se containers estão rodando
docker-compose ps

# 2. Verificar logs do frontend
docker-compose logs frontend | tail -20

# 3. Verificar configuração Nginx dentro do container
docker exec sindicofk-frontend cat /etc/nginx/conf.d/default.conf | head -5
# Deve mostrar: listen 443 ssl http2; (para produção)

# 4. Testar acesso
curl -I https://sindicofk.com.br
# Deve retornar: HTTP/2 200
```

---

## 📋 Checklist de Diagnóstico

### No VPS, verifique:

```bash
# 1. Arquivo nginx-temp.conf NÃO deve existir
ls -la frontend/nginx-temp.conf
# Deve retornar: "No such file or directory"

# 2. Arquivo nginx.conf DEVE existir
ls -la frontend/nginx.conf
# Deve mostrar o arquivo

# 3. Verificar configuração ativa no container
docker exec sindicofk-frontend cat /etc/nginx/conf.d/default.conf | grep "listen"
# Deve mostrar:
# listen 80;
# listen 443 ssl http2;

# 4. Certificados SSL existem
docker exec sindicofk-frontend ls -la /etc/letsencrypt/live/sindicofk.com.br/
# Deve mostrar: fullchain.pem, privkey.pem

# 5. Frontend está respondendo
curl -I http://localhost
# Deve retornar HTTP/1.1 301 (redirect para HTTPS)

curl -I https://sindicofk.com.br
# Deve retornar HTTP/2 200
```

---

## 🛠️ Problemas Comuns e Soluções

### 1. Container não inicia

```bash
# Ver erro específico
docker-compose logs frontend

# Reconstruir do zero
docker-compose down -v
docker-compose build --no-cache frontend
docker-compose up -d
```

### 2. Erro de certificado SSL

```bash
# Verificar certificados
docker-compose exec certbot certbot certificates

# Renovar certificados se necessário
docker-compose exec certbot certbot renew

# Reiniciar frontend
docker-compose restart frontend
```

### 3. Nginx usa configuração errada

```bash
# Forçar uso da configuração de produção
rm -f frontend/nginx-temp.conf
docker-compose down
docker-compose build --no-cache frontend
docker-compose up -d
```

### 4. Site redireciona mas não carrega

```bash
# Verificar permissões dos arquivos
docker exec sindicofk-frontend ls -la /usr/share/nginx/html/

# Verificar se index.html existe
docker exec sindicofk-frontend cat /usr/share/nginx/html/index.html | head -5

# Reiniciar Nginx
docker-compose restart frontend
```

---

## 🔄 Fluxo de Deploy Correto

### No Desenvolvimento (Local):

```bash
# Windows
.\start-local.bat

# OU Linux/Mac
./start-local.sh

# Isso cria nginx-temp.conf localmente (NÃO commitar)
```

### Ao Fazer Deploy (VPS):

```bash
# 1. No local, commitar mudanças (nginx-temp.conf é ignorado)
git add .
git commit -m "Atualização do site"
git push origin master

# 2. No VPS, fazer pull
git pull origin master

# 3. No VPS, fazer deploy
./deploy-vps.sh

# OU manualmente:
rm -f frontend/nginx-temp.conf
docker-compose down
docker-compose up -d --build
```

---

## 📁 Arquivos Importantes

| Arquivo | Ambiente | Commitar? | Descrição |
|---------|----------|-----------|-----------|
| `nginx.conf` | Produção | ✅ Sim | Configuração HTTPS (VPS) |
| `nginx-local.conf` | Desenvolvimento | ✅ Sim | Configuração HTTP (Local) |
| `nginx-temp.conf` | Temporário | ❌ Não | Criado pelos scripts (gitignore) |
| `start-local.bat` | Desenvolvimento | ✅ Sim | Script Windows (dev) |
| `deploy-vps.sh` | Produção | ✅ Sim | Script Linux (VPS) |

---

## ⚙️ Como o Dockerfile Funciona Agora

```dockerfile
# Prioridade de configuração:
1. Se nginx-temp.conf existe → Usa ele (desenvolvimento)
2. Se não existe → Usa nginx.conf (produção padrão)
```

**No Local:** `start-local.bat` cria `nginx-temp.conf` com configuração HTTP  
**No VPS:** Não existe `nginx-temp.conf`, então usa `nginx.conf` com HTTPS

---

## 🎯 Garantir que Nunca Mais Aconteça

### 1. No .gitignore (já configurado):

```
frontend/nginx-temp.conf
```

### 2. Sempre usar os scripts:

- **Local:** `start-local.bat`
- **VPS:** `deploy-vps.sh`

### 3. Nunca commitar nginx-temp.conf:

```bash
# Se acidentalmente adicionou:
git rm --cached frontend/nginx-temp.conf
git commit -m "Remove arquivo temporário"
git push
```

---

## 📞 Suporte Emergencial

Se nada funcionar, resetar completamente:

```bash
# NO VPS:

# 1. Backup do banco (se necessário)
docker-compose exec mysql mysqldump -u sindicofk -p sindicofk_crm > backup.sql

# 2. Parar tudo
docker-compose down -v

# 3. Limpar Docker
docker system prune -af
docker volume prune -f

# 4. Garantir configuração limpa
rm -f frontend/nginx-temp.conf
git checkout -- frontend/

# 5. Rebuild completo
docker-compose build --no-cache
docker-compose up -d

# 6. Verificar
docker-compose ps
docker-compose logs frontend
```

---

## ✅ Verificação Final

Após o deploy, confirme:

- [ ] https://sindicofk.com.br acessível
- [ ] Redirecionamento HTTP → HTTPS funcionando
- [ ] API responde em /api
- [ ] Certificado SSL válido (cadeado verde)
- [ ] Logs sem erros: `docker-compose logs frontend`
- [ ] Container frontend está "Up": `docker-compose ps`

---

**Criado em:** 06/02/2026  
**Última atualização:** 06/02/2026
