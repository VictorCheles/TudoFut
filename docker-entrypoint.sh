#!/bin/sh

# Ajustar permissões para evitar erros de escrita
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Esperar a pasta de build do Vue ser criada
echo "Aguardando o build do Vue..."
while [ ! -d "public/build" ]; do
  sleep 2
done

echo "Build finalizado! Iniciando Laravel..."
exec php-fpm
