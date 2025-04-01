FROM php:7.2-apache

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip gd

# Apache configuration
COPY ./apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copy the current directory contents to the container
COPY .. /var/www/html

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Enable Xdebug for development
# RUN pecl install xdebug && docker-php-ext-enable xdebug
# COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expose port 80 to the outside world
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
