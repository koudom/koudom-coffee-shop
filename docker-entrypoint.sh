#!/bin/sh
set -e

# Cloud Run sets the PORT env var at runtime (typically 8080)
# Configure Apache to listen on that port
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf
sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

exec "$@"
