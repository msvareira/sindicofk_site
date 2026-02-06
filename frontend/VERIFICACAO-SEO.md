# Guia Rápido de Verificação de SEO

## 🔍 Como Verificar as Otimizações

### 1. Verificar Meta Tags no Navegador

Abra qualquer página do site e pressione **F12** (DevTools), depois vá em **Elements** e procure no `<head>`:

```html
<!-- Você deverá ver: -->
<meta name="description" content="...">
<meta property="og:title" content="...">
<meta property="og:image" content="...">
<meta name="twitter:card" content="...">
<link rel="canonical" href="...">
<script type="application/ld+json">...</script>
```

---

### 2. Testar Open Graph (Facebook/WhatsApp)

1. Acesse: https://developers.facebook.com/tools/debug/
2. Cole a URL: `https://sindicofk.com.br`
3. Clique em **Debug**
4. Verifique se aparecem:
   - ✅ Título da página
   - ✅ Descrição
   - ✅ Imagem (logo)

---

### 3. Testar Twitter Cards

1. Acesse: https://cards-dev.twitter.com/validator
2. Cole a URL: `https://sindicofk.com.br`
3. Clique em **Preview card**
4. Verifique o preview do card

---

### 4. Validar Structured Data (JSON-LD)

**Opção 1 - Google Rich Results Test:**
1. Acesse: https://search.google.com/test/rich-results
2. Cole a URL ou o código HTML
3. Clique em **Test URL** ou **Test Code**
4. Verifique se aparecem os schemas detectados:
   - Organization
   - LocalBusiness
   - BreadcrumbList
   - Service
   - ContactPage

**Opção 2 - Schema Markup Validator:**
1. Acesse: https://validator.schema.org
2. Cole a URL
3. Clique em **Run Test**

---

### 5. Verificar Sitemap e Robots.txt

**Sitemap:**
- Acesse: `https://sindicofk.com.br/sitemap.xml`
- Deve mostrar lista XML com todas as páginas

**Robots.txt:**
- Acesse: `https://sindicofk.com.br/robots.txt`
- Deve mostrar as regras de crawling

---

### 6. Google PageSpeed Insights

1. Acesse: https://pagespeed.web.dev
2. Cole: `https://sindicofk.com.br`
3. Clique em **Analyze**
4. Metas:
   - ✅ Performance > 85
   - ✅ SEO = 100
   - ✅ Best Practices > 90
   - ✅ Accessibility > 90

---

### 7. Mobile-Friendly Test

1. Acesse: https://search.google.com/test/mobile-friendly
2. Cole a URL
3. Verifique se é mobile-friendly

---

### 8. Verificar HTTPS

1. Acesse: `https://sindicofk.com.br`
2. Verifique o cadeado na barra de endereço
3. Clique no cadeado → "A conexão é segura"

---

### 9. Lighthouse no Chrome DevTools

1. Abra o site no Chrome
2. Pressione **F12**
3. Vá na aba **Lighthouse**
4. Selecione:
   - ✅ Performance
   - ✅ Accessibility
   - ✅ Best Practices
   - ✅ SEO
5. Clique em **Generate report**
6. Analise os resultados

---

### 10. Verificar no View Source

Pressione **Ctrl+U** no navegador para ver o código-fonte e busque por:

```html
<!-- Canonical URL -->
<link rel="canonical" href="...">

<!-- Open Graph -->
<meta property="og:type" content="website">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">

<!-- Structured Data -->
<script type="application/ld+json">

<!-- PWA Manifest -->
<link rel="manifest" href="manifest.json">
```

---

## ✅ Checklist Rápido

Depois de fazer deploy, verifique:

- [ ] Site acessível via HTTPS
- [ ] Todas as páginas carregam corretamente
- [ ] `/sitemap.xml` acessível
- [ ] `/robots.txt` acessível
- [ ] `/manifest.json` acessível
- [ ] Open Graph funcionando (teste no Facebook Debugger)
- [ ] Twitter Cards funcionando
- [ ] Structured Data validado (Google Rich Results Test)
- [ ] PageSpeed Score > 85
- [ ] Mobile-Friendly confirmado
- [ ] Todas as imagens têm alt text
- [ ] Links funcionando corretamente

---

## 🚀 Após Verificação

Se tudo estiver OK:

1. **Submeter ao Google Search Console**
   - URL: https://search.google.com/search-console
   - Adicionar propriedade
   - Verificar propriedade
   - Enviar sitemap: `https://sindicofk.com.br/sitemap.xml`

2. **Submeter ao Bing Webmaster**
   - URL: https://www.bing.com/webmasters
   - Adicionar site
   - Enviar sitemap

3. **Criar Google Business Profile**
   - URL: https://www.google.com/business/
   - Cadastrar empresa

4. **Configurar Google Analytics**
   - URL: https://analytics.google.com
   - Criar propriedade
   - Adicionar código de tracking

---

## 📊 Monitoramento Contínuo

### Semanalmente
- Verificar posição no Google para palavras-chave principais
- Checar Google Analytics (tráfego orgânico)
- Responder reviews no Google Business

### Mensalmente
- Analisar Search Console (queries, impressões, cliques)
- Verificar PageSpeed (pode piorar com o tempo)
- Atualizar sitemap.xml se houver novas páginas
- Criar conteúdo novo (blog posts)

### Trimestralmente
- Auditar backlinks
- Revisar e atualizar meta descriptions
- Analisar concorrência
- Revisar estratégia de palavras-chave

---

## 🔧 Ferramentas Úteis

### Análise SEO
- **Google Search Console** - Monitoramento oficial do Google
- **Google Analytics** - Análise de tráfego
- **Ahrefs** ou **SEMrush** - Análise avançada (pago)
- **Ubersuggest** - Pesquisa de palavras-chave (freemium)

### Testes Técnicos
- **PageSpeed Insights** - Performance e Core Web Vitals
- **GTmetrix** - Análise de performance detalhada
- **Screaming Frog** - Crawler de SEO (freemium)
- **Google Mobile-Friendly Test** - Teste de responsividade

### Validação
- **Google Rich Results Test** - Structured data
- **Schema Markup Validator** - Validação de schemas
- **W3C Validator** - Validação de HTML
- **SSL Labs** - Teste de segurança SSL

---

## 💡 Dicas Importantes

1. **Paciência**: SEO leva tempo (3-6 meses para resultados significativos)
2. **Conteúdo**: Crie conteúdo relevante e atualizado regularmente
3. **Backlinks**: Qualidade > Quantidade
4. **Mobile**: Priorize a experiência mobile
5. **Velocidade**: Site rápido = melhor ranking
6. **Localização**: Otimize para buscas locais (Porto Alegre)
7. **Reviews**: Incentive avaliações no Google
8. **Social**: Mantenha redes sociais ativas

---

**Última atualização:** 06/02/2026
