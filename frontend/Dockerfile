# Frontend Dockerfile - Síndico FK
# Nginx para servir arquivos estáticos

FROM nginx:alpine

# Copiar arquivos do site para o diretório do nginx
COPY . /usr/share/nginx/html

# Copiar configuração customizada do nginx
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expor porta 80
EXPOSE 80

# Nginx em foreground
CMD ["nginx", "-g", "daemon off;"]
