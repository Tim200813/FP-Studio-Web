FROM php:8.1-cli

# Installiere systemweite Abhängigkeiten
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git

# Installiere Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Setze das Arbeitsverzeichnis
WORKDIR /app

# Kopiere die Composer-Dateien
COPY composer.json composer.lock ./

# Installiere PHP-Abhängigkeiten
RUN composer install

# Kopiere den Rest des Codes
COPY . .

CMD ["php", "index.php"]
