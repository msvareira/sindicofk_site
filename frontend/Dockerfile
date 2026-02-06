# Frontend Dockerfile - Síndico FK
# Nginx para servir arquivos estáticos

FROM nginx:alpine

# Criar diretório para certbot challenges
RUN mkdir -p /var/www/certbot && \
    chmod -R 755 /var/www/certbot

# Copiar arquivos do site para o diretório do nginx
COPY . /usr/share/nginx/html

# Copiar configuração customizada do nginx
# Usa nginx-temp.conf se existir (alternar entre local/producao), senão usa nginx.conf
COPY nginx*.conf /tmp/
RUN if [ -f /tmp/nginx-temp.conf ]; then \
        cp /tmp/nginx-temp.conf /etc/nginx/conf.d/default.conf; \
    else \
        cp /tmp/nginx.conf /etc/nginx/conf.d/default.conf; \
    fi

# Expor portas 80 e 443
EXPOSE 80 443

# Nginx em foreground
CMD ["nginx", "-g", "daemon off;"]
