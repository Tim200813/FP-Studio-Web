# Beispiel Dockerfile
FROM php:8.0-apache

# Installiere die erforderlichen Pakete
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Aktiviere das Apache-Modul
RUN a2enmod rewrite

# Setze das Arbeitsverzeichnis
WORKDIR /var/www/html

# Kopiere den aktuellen Inhalt in das Arbeitsverzeichnis
COPY . .

# Installiere Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
