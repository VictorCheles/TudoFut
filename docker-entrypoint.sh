#!/bin/sh

# Ajustar permissões para evitar erros de escrita
echo "🔧 Ajustando permissões..."
chown -R www-data:www-data storage bootstrap/cache vendor
chmod -R 775 storage bootstrap/cache vendor

#gera o .env
echo "🔧 Gerando .env..."
cp .env.example .env

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
