FROM php:8.4-apache

# Configure Apache to listen on the Cloud Run port
ENV PORT=8080
RUN sed -i "s/80/$PORT/g" /etc/apache2/ports.conf
RUN sed -i "s/80/$PORT/g" /etc/apache2/sites-available/000-default.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html

# Make the database directory writable for SQLite
RUN chown -R www-data:www-data /var/www/html/database

# Use the default Apache foreground command
CMD ["apache2-foreground"]
