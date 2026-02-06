# Carousel de Imagens - Síndico FK

## 📸 Galeria de Serviços

Um carousel moderno e responsivo foi adicionado à **página de Serviços** do site, exibindo 6 imagens dos serviços da empresa.

---

## 📍 Localização

O carousel está posicionado na **página Serviços** ([servicos.html](servicos.html)), logo após o cabeçalho da página e antes da seção de descrição detalhada dos serviços.

---

## ✨ Funcionalidades Implementadas

### 🎯 Navegação Múltipla

1. **Botões de Navegação**
   - Setas laterais (esquerda/direita)
   - Design moderno com fundo semi-transparente
   - Efeito hover com aumento de escala

2. **Indicadores (Dots)**
   - 6 pontos na parte inferior
   - Indicador ativo muda de formato (circular → retangular)
   - Clicável para ir direto ao slide desejado

3. **Teclado**
   - ← (Seta Esquerda): Slide anterior
   - → (Seta Direita): Próximo slide

4. **Toque em Mobile (Swipe)**
   - Deslize para esquerda: Próximo slide
   - Deslize para direita: Slide anterior
   - Detecção inteligente de swipe (mínimo 50px)

### ⚙️ Auto-Play Inteligente

- **Transição automática** a cada 5 segundos
- **Pausa ao passar o mouse** sobre o carousel
- **Pausa ao tocar** em dispositivos móveis
- **Pausa quando aba inativa** (economiza recursos)
- **Reinicia após interação manual**

### 🎨 Design e Estilo

- **Transições suaves** (fade in/out)
- **Imagens verticais** - Proporção 2:3 (1024x1536) sem corte
- **Object-fit: contain** - Imagem completa visível
- **Tamanho otimizado** - Container ajustado ao tamanho da imagem
- **Altura adaptável**:
  - Desktop: 900px (max-width: 600px)
  - Tablet: 600px (max-width: 400px)
  - Mobile: 420px (max-width: 280px)
- **Sombra e bordas arredondadas** para visual moderno
- **Background gradiente** sutil

### ♿ Acessibilidade

- **Aria-labels** em todos os botões
- **Alt text** em todas as imagens
- **Navegação por teclado** completa
- **Indicadores visuais** claros
- **Lazy loading** nas imagens (performance)

---

## 📂 Localização dos Arquivos

### Imagens
```
frontend/imagens/servicos/
├── 1.jpeg
├── 2.jpeg
├── 3.jpeg
├── 4.jpeg
├── 5.jpeg
└── 6.jpeg
```

### Código

**HTML:** [frontend/servicos.html](servicos.html)
- Linha: ~168-218
- Seção: `<section class="gallery-carousel">`

**CSS:** [frontend/css/style.css](css/style.css)
- Linha: ~1390-1570
- Classe principal: `.gallery-carousel`

**JavaScript:** [frontend/js/script.js](js/script.js)
- Linha: ~400-530
- Função: Gerenciamento completo do carousel

---

## 🎛️ Personalização

### Alterar Tempo de Auto-Play

No arquivo `js/script.js`, linha ~415:

```javascript
const autoPlayDelay = 5000; // 5 segundos
// Altere para o valor desejado em milissegundos
// Exemplo: 3000 = 3 segundos | 10000 = 10 segundos
```

### Alterar Altura do Carousel

No arquivo `css/style.css`, linha ~1415:

```css
.carousel-container {
    height: 900px;     /* Altura desktop (proporção 2:3) */
}

.carousel-wrapper {
    max-width: 600px;  /* Largura máxima */
}

@media (max-width: 768px) {
    .carousel-wrapper {
        max-width: 400px;
    }
    
    .carousel-container {
        height: 600px; /* Tablet */
    }
}

@media (max-width: 480px) {
    .carousel-wrapper {
        max-width: 280px;
    }
    
    .carousel-container {
        height: 420px; /* Mobile */
    }
}
```

### Alterar Velocidade da Transição

No arquivo `css/style.css`:

```css
.carousel-slide {
    transition: opacity 0.6s ease-in-out;
    /* Altere 0.6s para o valor desejado */
    /* Exemplo: 0.3s (rápido) ou 1s (lento) */
}
```

### Alterar Cores dos Botões

No arquivo `css/style.css`, linha ~1450:

```css
.carousel-btn {
    background: rgba(11, 83, 69, 0.8); /* Verde do logo */
}

.carousel-btn:hover {
    background: var(--color-primary); /* Verde sólido */
}
```

### Adicionar/Remover Imagens

1. Adicione a imagem na pasta `imagens/servicos/`
2. No `servicos.html`, adicione um novo slide:

```html
<div class="carousel-slide">
    <img src="imagens/servicos/7.jpeg" alt="Serviços Síndico FK - Imagem 7" loading="lazy">
</div>
```

3. Adicione um novo indicador:

```html
<button class="carousel-indicator" data-slide="6" aria-label="Ir para slide 7"></button>
```

---

## 🚀 Recursos Técnicos

### Performance

- ✅ **Lazy loading** nas imagens
- ✅ **CSS transitions** (melhor que animações JS)
- ✅ **Debounce** em eventos de touch
- ✅ **Auto-play pausado** em aba inativa
- ✅ **Imagens otimizadas** (recomendado)

### Compatibilidade

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Opera 76+
- ✅ Dispositivos iOS e Android

### SEO

- ✅ Alt text em todas as imagens
- ✅ Estrutura semântica HTML5
- ✅ Lazy loading para performance
- ✅ Aria-labels para acessibilidade

---

## 🐛 Resolução de Problemas

### Imagens não aparecem

1. Verifique se as imagens estão na pasta correta:
   ```
   frontend/imagens/servicos/1.jpeg
   ```

2. Verifique o formato do arquivo (deve ser .jpeg ou .jpg)

3. Verifique permissões de leitura no servidor

4. Abra o DevTools (F12) e veja erros no Console

### Carousel não funciona

1. Verifique se o JavaScript está carregando:
   ```html
   <script src="js/script.js"></script>
   ```

2. Abra o Console (F12) e procure por erros

3. Verifique se todas as classes CSS estão corretas

4. Limpe o cache do navegador (Ctrl+Shift+R)

### Auto-play não funciona

1. Verifique se há erros no Console
2. Teste em uma aba ativa (auto-play pausa em abas inativas)
3. Verifique se não há `preventDefault()` em eventos

---

## 📱 Responsividade

### Desktop (> 768px)
- Largura máxima: 600px
- Altura: 900px
- Botões: 50px
- Indicadores: 12px

### Tablet (481px - 768px)
- Largura máxima: 400px
- Altura: 600px
- Botões: 40px
- Indicadores: 10px

### Mobile (≤ 480px)
- Largura máxima: 280px
- Altura: 420px
- Botões: 35px
- Indicadores: 8px

---

## 🎨 Customizações Futuras

### Possíveis Melhorias

1. **Efeito de transição diferente**
   - Fade atual ✅ Implementado
   - Slide horizontal
   - Zoom
   - Fade com scale

2. **Thumbnails**
   - Miniaturas clicáveis
   - Preview dos slides

3. **Lightbox**
   - Clique para ampliar
   - Visualização em tela cheia

4. **Legendas**
   - Texto descritivo em cada slide
   - Título e descrição

5. **Contador**
   - Exibir "1/6", "2/6", etc.

6. **Progress Bar**
   - Barra de progresso do auto-play

---

## 📊 Estrutura do Código

### HTML
```
<section class="gallery-carousel">
  └── <div class="carousel-wrapper">
      ├── <div class="carousel-container">
      │   └── <div class="carousel-slide"> × 6
      ├── <button class="carousel-btn-prev">
      ├── <button class="carousel-btn-next">
      └── <div class="carousel-indicators">
          └── <button class="carousel-indicator"> × 6
```

### JavaScript (Fluxo)
```
1. DOMContentLoaded
2. Verificar se carousel existe
3. Selecionar elementos
4. Definir funções:
   - showSlide()
   - nextSlide()
   - prevSlide()
   - startAutoPlay()
   - stopAutoPlay()
5. Adicionar event listeners
6. Iniciar auto-play
```

---

## ✅ Checklist de Verificação

Após implementação, verifique:

- [ ] Todas as 6 imagens carregam
- [ ] Botões de navegação funcionam
- [ ] Indicadores mudam ao clicar
- [ ] Auto-play está funcionando (5s)
- [ ] Pausa ao passar o mouse
- [ ] Swipe funciona no mobile
- [ ] Setas do teclado funcionam
- [ ] Transições são suaves
- [ ] Design responsivo em todos os tamanhos
- [ ] Alt text em todas as imagens
- [ ] Sem erros no Console

---

## 🔗 Referências

- [MDN: IntersectionObserver](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
- [CSS Transitions](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Transitions)
- [Touch Events](https://developer.mozilla.org/en-US/docs/Web/API/Touch_events)
- [Accessibility](https://www.w3.org/WAI/WCAG21/quickref/)

---

**Criado em:** 06/02/2026  
**Status:** ✅ Implementado e funcional  
**Versão:** 1.0
