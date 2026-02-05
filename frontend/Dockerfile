# Frontend Dockerfile - Síndico FK
# Nginx para servir arquivos estáticos

FROM nginx:alpine

# Criar diretório para certbot challenges
RUN mkdir -p /var/www/certbot && \
    chmod -R 755 /var/www/certbot

# Copiar arquivos do site para o diretório do nginx
COPY . /usr/share/nginx/html

# Copiar configuração customizada do nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expor portas 80 e 443
EXPOSE 80 443

# Nginx em foreground
CMD ["nginx", "-g", "daemon off;"]
