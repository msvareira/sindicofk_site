/**
 * Configurações da aplicação Síndico FK
 * Gerencia URLs e configurações baseadas no ambiente
 */

const SindicoFKConfig = {
    // Detectar ambiente
    isDevelopment: window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1',
    
    // URLs da API
    getApiUrl() {
        // Em desenvolvimento, usar localhost:8080
        // Em produção, usar a mesma origem via proxy Nginx
        if (this.isDevelopment) {
            return 'http://localhost:8080/api';
        }
        
        // Em produção, usar a mesma origem (Nginx faz proxy para backend)
        // Isso garante HTTPS e evita problemas de CORS e Mixed Content
        return window.location.origin + '/api';
    },
    
    // WhatsApp
    whatsapp: {
        number: '5551982695830',
        getUrl(message = '') {
            const encodedMessage = encodeURIComponent(message);
            return `https://wa.me/${this.number}${message ? '?text=' + encodedMessage : ''}`;
        }
    },
    
    // E-mails
    email: {
        principal: 'adm.sindicofk@gmail.com',
        alternativo: 'sindicofk@gmail.com'
    },
    
    // Redes sociais
    social: {
        facebook: 'https://www.facebook.com/sindicofk.com.br?locale=pt_BR',
        instagram: 'https://www.instagram.com/sindicofk'
    },
    
    // Logging (apenas em desenvolvimento)
    log(...args) {
        if (this.isDevelopment) {
            console.log('[SindicoFK]', ...args);
        }
    },
    
    error(...args) {
        if (this.isDevelopment) {
            console.error('[SindicoFK Error]', ...args);
        }
    }
};

// Fazer disponível globalmente
window.SindicoFKConfig = SindicoFKConfig;

// Log de inicialização
SindicoFKConfig.log('Configurações carregadas', {
    ambiente: SindicoFKConfig.isDevelopment ? 'Desenvolvimento' : 'Produção',
    apiUrl: SindicoFKConfig.getApiUrl()
});
