FROM php:8.1-apache

# Install PHP extension
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy everything
COPY . .

# Give proper permissions
RUN chown -R www-data:www-data /var/www/html/storage

# Expose port 80
EXPOSE 80
