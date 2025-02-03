# Etapa 1: Base Ubuntu sem interface gráfica
FROM ubuntu:20.04

# Definir frontend não interativo para evitar prompts durante a instalação
ENV DEBIAN_FRONTEND=noninteractive

# Atualizar repositórios e instalar dependências essenciais
RUN apt-get update && apt-get install -y \
    curl \
    gnupg \
    ca-certificates \
    lsb-release \
    apt-transport-https \
    software-properties-common \
    unzip \
    git \
    nginx \
    supervisor

# Instalar Node.js 20.x (LTS) + NPM atualizado
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm install -g npm@latest

# Instalar PHP 8.3 + extensões necessárias para Laravel
RUN add-apt-repository ppa:ondrej/php -y && \
    apt-get update && \
    apt-get install -y php8.3 php8.3-cli php8.3-fpm php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd php8.3-tokenizer php8.3-pdo php8.3-mysql && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir diretório de trabalho
WORKDIR /var/www/html

# Copiar arquivos do projeto para o container
COPY . .

# Instalar dependências do Laravel e Vue
RUN composer install --no-dev --optimize-autoloader && \
    npm install && \
    npm run build

# Ajustar permissões das pastas de cache e logs do Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuração do Nginx
COPY nginx/default.conf /etc/nginx/sites-available/default

# Expor portas do Nginx
EXPOSE 80

CMD service php8.3-fpm start && nginx -g 'daemon off;'
