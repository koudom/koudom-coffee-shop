FROM php:8.4-apache

# Enable Apache mod_rewrite and MySQL PDO support.
RUN a2enmod rewrite \
    && docker-php-ext-install pdo_mysql

# Copy application files
COPY . /var/www/html

# Make the database directory writable for SQLite
RUN chown -R www-data:www-data /var/www/html/database

# Copy runtime entrypoint that reads PORT env var
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
