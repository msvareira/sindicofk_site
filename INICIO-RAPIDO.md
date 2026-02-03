# 🚀 Guia de Início Rápido - Síndico FK

## Para Usuários Windows

### Primeira Vez (Configuração Inicial)

1. **Certifique-se que o Docker Desktop está rodando**
   - Abra o Docker Desktop
   - Aguarde até o ícone ficar verde

2. **Execute o script de inicialização**
   ```
   Dê duplo-clique em: start.bat
   ```

3. **Configure o backend (apenas primeira vez)**
   ```
   Dê duplo-clique em: setup.bat
   ```

4. **Pronto! Acesse:**
   - Site: http://localhost
   - Painel de E-mails: http://localhost:8025

### Uso Diário

**Iniciar o sistema:**
```
Duplo-clique em: start.bat
```

**Parar o sistema:**
```
Duplo-clique em: stop.bat
```

## URLs Importantes

| Serviço | URL | Descrição |
|---------|-----|-----------|
| Site | http://localhost | Frontend do site |
| API | http://localhost:8080/api | Backend API |
| API Health | http://localhost:8080/api/health | Status da API |
| Mailpit | http://localhost:8025 | Ver e-mails de teste |

## Comandos Úteis (PowerShell)

### Ver logs em tempo real
```powershell
docker-compose logs -f
```

### Ver status dos containers
```powershell
docker-compose ps
```

### Reiniciar um serviço específico
```powershell
docker-compose restart backend
```

### Entrar no container do backend
```powershell
docker-compose exec backend bash
```

### Limpar tudo e recomeçar
```powershell
docker-compose down -v
docker-compose up -d --build
```

## Problemas Comuns

### "Porta já está em uso"
- Verifique se há outro serviço usando a porta 80 ou 8080
- Pode parar o IIS se estiver rodando: `iisreset /stop`

### "Docker não está rodando"
- Abra o Docker Desktop
- Aguarde alguns segundos até inicializar completamente

### "Erro ao conectar no banco de dados"
- Aguarde 30 segundos após iniciar os containers
- O MySQL demora um pouco para inicializar na primeira vez

### "Formulário não envia"
- Verifique se todos os containers estão rodando: `docker-compose ps`
- Abra o console do navegador (F12) para ver erros
- Verifique se a API está respondendo: http://localhost:8080/api/health

## Testar o Sistema

1. Acesse http://localhost/contato.html
2. Preencha o formulário
3. Clique em "Enviar Mensagem"
4. Verifique os e-mails em http://localhost:8025

## Estrutura de Pastas

```
├── frontend/          # Site (HTML, CSS, JS)
├── backend/           # API Laravel
├── start.bat         # Iniciar sistema
├── stop.bat          # Parar sistema
├── setup.bat         # Configuração inicial
└── docker-compose.yml # Configuração Docker
```

## Dicas

- **Desenvolvimento:** Os arquivos são atualizados automaticamente, não precisa reiniciar
- **E-mails:** Em desenvolvimento, todos os e-mails vão para o Mailpit (localhost:8025)
- **Logs:** Use `docker-compose logs -f backend` para ver erros do backend
- **Cache:** Se algo não funcionar, tente: `docker-compose restart backend`

## Próximos Passos

1. Personalize o conteúdo em `frontend/*.html`
2. Ajuste estilos em `frontend/css/style.css`
3. Configure e-mails de produção em `backend/.env`
4. Teste o formulário de contato

## Precisa de Ajuda?

- 📧 E-mail: adm.sindicofk@gmail.com
- 📱 WhatsApp: (51) 98269-5830
- 📖 Documentação completa: Veja README.md
