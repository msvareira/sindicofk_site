# SÍNDICO FK - SOLUÇÕES EM CONDOMÍNIOS LTDA
## Site Institucional Profissional

Site desenvolvido para a empresa Síndico FK, especializada em gestão profissional de condomínios.

---

## 📋 Estrutura do Projeto

```
Site/
├── index.html              # Página inicial (Home)
├── quem-somos.html         # Página Quem Somos
├── servicos.html           # Página de Serviços
├── contato.html            # Página de Contato
├── css/
│   └── style.css          # Estilos globais
├── js/
│   └── script.js          # JavaScript principal
└── imagens/
    └── logo.png           # Logo da empresa
```

---

## 🎨 Cores do Projeto

Baseadas no logo da empresa:

- **Verde Esmeralda:** `#0B5345` (Cor principal)
- **Dourado:** `#D4AF37` (Cor secundária/destaque)
- **Branco:** `#FFFFFF`
- **Cinza Claro:** `#F5F5F5`

---

## ✨ Funcionalidades Implementadas

### 1. **Home (index.html)**
- Hero section impactante com CTA
- Seção de benefícios (6 cards)
- Preview de serviços
- Call-to-action para contato
- Botão WhatsApp flutuante

### 2. **Quem Somos (quem-somos.html)**
- Apresentação institucional
- Missão, Visão e Valores
- Diferenciais da empresa
- Design profissional e confiável

### 3. **Serviços (servicos.html)**
- Síndico Profissional
- Gestão Administrativa e Financeira
- Acompanhamento de Obras e Contratos
- Atendimento a Moradores
- Conformidade Legal e Assembleias
- Segurança e Controle de Acesso

### 4. **Contato (contato.html)**
- Formulário funcional com validação
- Informações de contato
- Integração com WhatsApp
- Links para redes sociais

### 5. **Design Responsivo**
- Desktop (1200px+)
- Tablet (768px - 1024px)
- Mobile (até 768px)

### 6. **Funcionalidades JavaScript**
- Menu mobile responsivo
- Scroll suave
- Validação de formulário em tempo real
- Máscara de telefone
- Animações ao rolar a página
- Prevenção de spam no formulário

---

## 🚀 Como Usar

### Abrindo o Site Localmente

1. Certifique-se de que todos os arquivos estão na estrutura correta
2. Abra o arquivo `index.html` em um navegador web moderno
3. Navegue entre as páginas através do menu

### Publicando o Site

O site pode ser hospedado em qualquer servidor web. Opções recomendadas:

- **Netlify** (gratuito e fácil)
- **Vercel** (gratuito)
- **GitHub Pages** (gratuito)
- **Servidor próprio** (cPanel, FTP, etc.)

---

## ⚙️ Configurações Necessárias

### 1. Número do WhatsApp
Substitua `5500000000000` pelo número real nos seguintes arquivos:
- `index.html` (linha 260 e 311)
- `quem-somos.html` (linha 265 e 316)
- `servicos.html` (linha 397 e 448)
- `contato.html` (linha 191, 231, 282 e 333)

**Formato:** `55` + `DDD` + `Número` (ex: `5511987654321`)

### 2. Links de Redes Sociais
Substitua `#` pelos links reais das redes sociais em todos os arquivos HTML:
- Facebook
- Instagram
- LinkedIn
- WhatsApp

### 3. Integração do Formulário

O formulário está configurado para funcionar localmente. Para produção, você precisa:

**Opção 1: Usar serviço de formulário**
- [Formspree](https://formspree.io/) - Gratuito
- [EmailJS](https://www.emailjs.com/) - Gratuito
- [Basin](https://usebasin.com/) - Gratuito

**Opção 2: Backend próprio**
- PHP com PHPMailer
- Node.js com Nodemailer
- API REST personalizada

**Exemplo com Formspree:**
```html
<form action="https://formspree.io/f/SEU_ID" method="POST">
```

---

## 📱 SEO e Performance

### Meta Tags Implementadas
- Title e Description otimizados
- Keywords relevantes
- Open Graph (pode ser adicionado)
- Viewport configurado

### Otimizações
- Código semântico (HTML5)
- Estrutura de headings correta (H1, H2, H3)
- URLs amigáveis
- Imagens otimizadas
- CSS e JS minificados (recomendado para produção)

---

## 🛠️ Melhorias Futuras Sugeridas

1. **Analytics**
   - Google Analytics
   - Facebook Pixel
   - Hotjar

2. **Chat Online**
   - Integração com Tawk.to
   - Zendesk Chat
   - WhatsApp Business API

3. **Blog**
   - Seção de artigos sobre gestão condominial
   - Dicas e novidades

4. **Área do Cliente**
   - Portal de acesso para condôminos
   - Relatórios financeiros
   - Abertura de chamados

5. **Certificações e Prêmios**
   - Seção de certificados
   - Depoimentos de clientes

6. **Otimizações Técnicas**
   - Lazy loading de imagens
   - Service Worker (PWA)
   - Compressão de assets

---

## 📧 Contatos da Empresa

- **E-mail Principal:** adm.sindicofk@gmail.com
- **E-mail Alternativo:** sindicofk@gmail.com
- **WhatsApp:** (Configurar número real)

---

## 📝 Tecnologias Utilizadas

- HTML5
- CSS3 (Flexbox, Grid, Animations)
- JavaScript (ES6+)
- Font Awesome 6.4.0
- Google Fonts (Poppins)

---

## 🔒 Segurança

Antes de publicar em produção:

1. Configure HTTPS (SSL/TLS)
2. Implemente proteção contra spam no formulário
3. Adicione Google reCAPTCHA
4. Configure headers de segurança
5. Faça backup regular dos dados

---

## 📄 Licença

© 2026 Síndico FK - Soluções em Condomínios LTDA. Todos os direitos reservados.

---

## 🤝 Suporte

Para suporte técnico ou dúvidas sobre o site, entre em contato através dos canais oficiais da empresa.

---

**Desenvolvido com dedicação para Síndico FK** 🏢✨
