#!/bin/sh
set -e

PORT="${PORT:-8080}"

sed -i -E "s/^Listen [0-9]+$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -i -E "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

# Suppress ServerName warning
if ! grep -q '^ServerName localhost$' /etc/apache2/apache2.conf; then
    echo "ServerName localhost" >> /etc/apache2/apache2.conf
fi

echo "Starting Apache on PORT=${PORT}"
apache2ctl configtest

exec docker-php-entrypoint "$@"
