#!/bin/sh

# Ajustar permissões para evitar erros de escrita
echo "🔧 Ajustando permissões..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor /var/www/html/storage/logs
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/vendor /var/www/html/storage/logs

# Instalar dependências PHP
echo "📦 Instalando dependências do Composer..."
composer install --no-interaction --prefer-dist

# Instalar dependências do Node.js
echo "📦 Instalando dependências do NPM..."
npm install && npm run dev

#gera a chave da aplicação
echo "🔧 Gerando chave da aplicação..."
php artisan key:generate

# Rodar as migrações automaticamente
echo "⚙️ Rodando migrações..."
php artisan migrate --force

# Esperar a pasta de build do Vue ser criada
echo "⏳ Aguardando o build do Vue..."
while [ ! -d "public/build" ]; do
  sleep 2
done

echo "✅ Build finalizado! Iniciando Laravel..."
exec php-fpm
