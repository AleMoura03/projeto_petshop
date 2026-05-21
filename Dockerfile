# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip git && \
    docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Enable Apache rewrite module (optional, for Laravel routing)
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application source
COPY . .

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install PHP dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader

# Install Node.js (using NodeSource) and build assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs && \
    npm ci && \
    npm run build

# Ensure proper permissions for Laravel storage & cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Expose port (Render will map $PORT)
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
