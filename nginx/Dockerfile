FROM nginx:alpine

# Copy SSL initialization script
COPY init-ssl.sh /docker-entrypoint.d/00-init-ssl.sh
RUN chmod +x /docker-entrypoint.d/00-init-ssl.sh

# Copy Nginx configuration
COPY default.conf /etc/nginx/conf.d/default.conf

# Create SSL directory
RUN mkdir -p /etc/nginx/ssl

CMD ["nginx", "-g", "daemon off;"]