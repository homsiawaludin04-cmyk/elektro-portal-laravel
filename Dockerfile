FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure freetype --with-freetype \
    && docker-php-ext-install pdo_mysql gd

# Aktifkan modul rewrite Apache untuk routing Laravel
RUN a2enmod rewrite

# Install Composer secara otomatis
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur folder kerja ke Apache web root
WORKDIR /var/www/html

# Salin semua file projek Laravel ke dalam container cloud
COPY . .

# Install dependency Laravel via Composer
RUN composer install --no-dev --optimize-autoloader

# Atur hak akses folder storage dan cache agar Laravel bisa menulis log
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Ubah target root Apache agar mengarah ke folder public Laravel
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Buka port standar untuk web server
EXPOSE 80

# Jalankan server Apache bawaan
CMD ["apache2-foreground"]
