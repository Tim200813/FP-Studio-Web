# Nutze die offizielle PHP-Apache-Image
FROM php:8.2-apache

# Installiere Systempakete und benötigte PHP-Erweiterungen
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_pgsql

# Aktiviere Apache-Module
RUN a2enmod rewrite

# Setze das Arbeitsverzeichnis
WORKDIR /var/www/html

# Kopiere Composer aus offiziellem Image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Kopiere Projektdateien in den Container
COPY . .

# Installiere Composer-Abhängigkeiten
RUN composer install --no-dev --optimize-autoloader

# Starte den Apache-Server
CMD ["apache2-foreground"]
