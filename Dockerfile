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

# Kopiere den aktuellen Code in das Container-Image
COPY . .

# Installiere Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Starte den Apache-Server
CMD ["apache2-foreground"]
