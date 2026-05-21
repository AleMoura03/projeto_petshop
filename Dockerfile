# Use the official PHP image with Apache
FROM php:8.2-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# ----- 3️⃣ Instalar dependências do sistema -----
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# ----- 4️⃣ Diretório de trabalho -----
WORKDIR /var/www/html

# ----- 5️⃣ Copiar código da aplicação -----
COPY . .

# ----- 6️⃣ Instalar Composer e dependências PHP (sem dev) -----
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader

# ----- 7️⃣ Instalar Node.js e compilar assets -----
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm ci && npm run build

# ----- 8️⃣ Ajustar permissões -----
RUN chown -R www-data:www-data storage bootstrap/cache public

# ----- 9️⃣ Expor porta (Render usará $PORT) -----
EXPOSE 80

# ----- 🔟 Iniciar Apache -----
CMD ["apache2-foreground"]
