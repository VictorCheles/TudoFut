FROM php:8.4-fpm

# Instalar extensões do PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html

RUN mkdir -p /var/www/html/vendor

# Ajustar permissões para evitar erros de escrita
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor

# Instalar dependências do Laravel no build
RUN composer install --no-dev --no-interaction --prefer-dist

CMD ["php-fpm"]
