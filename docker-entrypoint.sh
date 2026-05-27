#!/bin/sh
set -e

PORT="${PORT:-8080}"

sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

# Suppress ServerName warning
echo "ServerName localhost" >> /etc/apache2/apache2.conf

exec "$@"
