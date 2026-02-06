# Frontend Dockerfile - Síndico FK
# Nginx para servir arquivos estáticos

FROM nginx:alpine

# Criar diretório para certbot challenges
RUN mkdir -p /var/www/certbot && \
    chmod -R 755 /var/www/certbot

# Copiar arquivos do site para o diretório do nginx
COPY . /usr/share/nginx/html

# Copiar configuração customizada do nginx
# Prioridade: nginx-temp.conf (dev/local) > nginx.conf (produção padrão)
RUN if [ -f /usr/share/nginx/html/nginx-temp.conf ]; then \
        echo "Usando nginx-temp.conf (desenvolvimento)"; \
        cp /usr/share/nginx/html/nginx-temp.conf /etc/nginx/conf.d/default.conf; \
    else \
        echo "Usando nginx.conf (produção)"; \
        cp /usr/share/nginx/html/nginx.conf /etc/nginx/conf.d/default.conf; \
    fi

# Expor portas 80 e 443
EXPOSE 80 443

# Nginx em foreground
CMD ["nginx", "-g", "daemon off;"]
