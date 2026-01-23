// ============================================
// SÍNDICO FK - JAVASCRIPT
// Funcionalidades: Menu mobile, formulário e interações
// ============================================

// ============================================
// MENU MOBILE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    const body = document.body;
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.classList.toggle('active');
            body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
            
            // Animar o ícone hambúrguer
            const spans = this.querySelectorAll('span');
            if (this.classList.contains('active')) {
                spans[0].style.transform = 'rotate(45deg) translate(8px, 8px)';
                spans[1].style.opacity = '0';
                spans[2].style.transform = 'rotate(-45deg) translate(7px, -7px)';
            } else {
                spans[0].style.transform = 'none';
                spans[1].style.opacity = '1';
                spans[2].style.transform = 'none';
            }
        });
        
        // Fechar menu ao clicar em um link
        const navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    navMenu.classList.remove('active');
                    mobileMenuToggle.classList.remove('active');
                    body.style.overflow = '';
                    
                    const spans = mobileMenuToggle.querySelectorAll('span');
                    spans[0].style.transform = 'none';
                    spans[1].style.opacity = '1';
                    spans[2].style.transform = 'none';
                }
            });
        });
    }
});

// ============================================
// SCROLL SUAVE
// ============================================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#' && href !== '') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// ============================================
// HEADER SCROLL
// ============================================
let lastScroll = 0;
const header = document.querySelector('.header');

window.addEventListener('scroll', function() {
    const currentScroll = window.pageYOffset;
    
    // Adicionar sombra ao header ao rolar
    if (currentScroll > 50) {
        header.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
    } else {
        header.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
    }
    
    lastScroll = currentScroll;
});

// ============================================
// FORMULÁRIO DE CONTATO
// ============================================
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Obter dados do formulário
        const formData = {
            nome: document.getElementById('nome').value,
            telefone: document.getElementById('telefone').value,
            email: document.getElementById('email').value,
            condominio: document.getElementById('condominio').value,
            assunto: document.getElementById('assunto').value,
            mensagem: document.getElementById('mensagem').value
        };
        
        // Validação básica
        if (!formData.nome || !formData.telefone || !formData.email || !formData.mensagem) {
            showFormMessage('Por favor, preencha todos os campos obrigatórios.', 'error');
            return;
        }
        
        // Validar email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(formData.email)) {
            showFormMessage('Por favor, insira um e-mail válido.', 'error');
            return;
        }
        
        // Simular envio (em produção, integrar com backend)
        const submitButton = contactForm.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        
        // Simular delay de envio
        setTimeout(function() {
            // Criar mensagem para WhatsApp como fallback
            const whatsappMessage = `
                *Nova Solicitação de Contato - Síndico FK*
                
                *Nome:* ${formData.nome}
                *Telefone:* ${formData.telefone}
                *E-mail:* ${formData.email}
                *Condomínio:* ${formData.condominio || 'Não informado'}
                *Assunto:* ${formData.assunto || 'Não especificado'}
                
                *Mensagem:*
                ${formData.mensagem}
            `.trim();
            
            // Exibir mensagem de sucesso
            showFormMessage('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
            
            // Limpar formulário
            contactForm.reset();
            
            // Restaurar botão
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Mensagem';
            
            // Opcional: redirecionar para WhatsApp após 2 segundos
            // setTimeout(() => {
            //     const whatsappUrl = `https://wa.me/5500000000000?text=${encodeURIComponent(whatsappMessage)}`;
            //     window.open(whatsappUrl, '_blank');
            // }, 2000);
            
            console.log('Dados do formulário:', formData);
        }, 1500);
    });
}

// ============================================
// FUNÇÃO PARA EXIBIR MENSAGENS DO FORMULÁRIO
// ============================================
function showFormMessage(message, type) {
    const formMessage = document.getElementById('formMessage');
    if (formMessage) {
        formMessage.textContent = message;
        formMessage.className = `form-message ${type}`;
        
        // Rolar até a mensagem
        formMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        
        // Remover mensagem após 5 segundos
        if (type === 'success') {
            setTimeout(() => {
                formMessage.className = 'form-message';
            }, 5000);
        }
    }
}

// ============================================
// MÁSCARA DE TELEFONE
// ============================================
const telefoneInput = document.getElementById('telefone');
if (telefoneInput) {
    telefoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        if (value.length <= 11) {
            if (value.length <= 10) {
                // (00) 0000-0000
                value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            } else {
                // (00) 00000-0000
                value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
                value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            }
        }
        
        e.target.value = value;
    });
}

// ============================================
// ANIMAÇÃO DE ELEMENTOS AO ROLAR
// ============================================
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observar elementos com animação
document.addEventListener('DOMContentLoaded', function() {
    const animatedElements = document.querySelectorAll(
        '.benefit-card, .service-preview-card, .mvv-card, .differential-item, .service-detail'
    );
    
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(30px)';
        element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(element);
    });
});

// ============================================
// CONTADOR DE SCROLL (HERO)
// ============================================
const heroScroll = document.querySelector('.hero-scroll');
if (heroScroll) {
    heroScroll.addEventListener('click', function() {
        const nextSection = document.querySelector('.benefits, .about-section');
        if (nextSection) {
            nextSection.scrollIntoView({ behavior: 'smooth' });
        }
    });
}

// ============================================
// WHATSAPP FLUTUANTE - TRACKING
// ============================================
const whatsappFloat = document.querySelector('.whatsapp-float');
if (whatsappFloat) {
    whatsappFloat.addEventListener('click', function() {
        console.log('WhatsApp button clicked');
        // Aqui você pode adicionar tracking analytics
        // Ex: gtag('event', 'whatsapp_click', { ... });
    });
}

// ============================================
// VALIDAÇÃO DE EMAIL EM TEMPO REAL
// ============================================
const emailInput = document.getElementById('email');
if (emailInput) {
    emailInput.addEventListener('blur', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.style.borderColor = '#dc3545';
            showTooltip(this, 'Por favor, insira um e-mail válido');
        } else {
            this.style.borderColor = '';
            hideTooltip(this);
        }
    });
    
    emailInput.addEventListener('input', function() {
        this.style.borderColor = '';
        hideTooltip(this);
    });
}

// ============================================
// TOOLTIP HELPERS
// ============================================
function showTooltip(element, message) {
    let tooltip = element.nextElementSibling;
    if (!tooltip || !tooltip.classList.contains('tooltip')) {
        tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.style.cssText = 'color: #dc3545; font-size: 0.875rem; margin-top: 0.25rem;';
        element.parentNode.appendChild(tooltip);
    }
    tooltip.textContent = message;
}

function hideTooltip(element) {
    const tooltip = element.nextElementSibling;
    if (tooltip && tooltip.classList.contains('tooltip')) {
        tooltip.remove();
    }
}

// ============================================
// PREVENÇÃO DE SPAM NO FORMULÁRIO
// ============================================
let formSubmitted = false;
if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        if (formSubmitted) {
            e.preventDefault();
            showFormMessage('Por favor, aguarde antes de enviar novamente.', 'error');
            return false;
        }
        formSubmitted = true;
        setTimeout(() => { formSubmitted = false; }, 5000);
    });
}

// ============================================
// LOADING LAZY DE IMAGENS
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                imageObserver.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
});

// ============================================
// PERFORMANCE - DEBOUNCE PARA SCROLL
// ============================================
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Aplicar debounce no scroll
const debouncedScroll = debounce(function() {
    // Funções de scroll otimizadas aqui
}, 100);

window.addEventListener('scroll', debouncedScroll);

// ============================================
// LOG DE DESENVOLVIMENTO
// ============================================
console.log('%c🏢 Síndico FK - Site Institucional', 'color: #0B5345; font-size: 18px; font-weight: bold;');
console.log('%cSite carregado com sucesso!', 'color: #D4AF37; font-size: 14px;');
console.log('%cDesenvolvido com HTML5, CSS3 e JavaScript', 'color: #666; font-size: 12px;');
