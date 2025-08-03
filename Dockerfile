# Dockerfile para Byte Bank API - Laravel 12
FROM php:8.2-fpm-alpine

# Instalar dependências do sistema
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    supervisor \
    nginx \
    && docker-php-ext-install pdo pdo_mysql gd xml

# Configurar diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos de configuração do composer
COPY composer.json composer.lock ./

# Instalar dependências do PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copiar código da aplicação
COPY . .

# Instalar dependências do Node.js e build dos assets
RUN npm install && npm run build

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copiar configurações do nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf

# Copiar configuração do supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expor porta 80
EXPOSE 80

# Comando para iniciar o supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"] 