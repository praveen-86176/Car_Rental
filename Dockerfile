FROM php:8.2-apache

# Fix the "More than one MPM loaded" error
RUN a2dismod mpm_event || true && \
    a2enmod mpm_prefork

# Enable required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy project files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
