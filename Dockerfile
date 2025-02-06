# Verwende das PHP-Image mit Apache
FROM php:8.1-apache

# Installiere notwendige PHP-Erweiterungen (MySQL, PostgreSQL und andere)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql

# Setze ServerName für Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Setze index.php als Standard
RUN echo "DirectoryIndex index.php" > /etc/apache2/mods-enabled/dir.conf

# Kopiere Projektdateien in den Webserver-Ordner
COPY . /var/www/html/

# Setze das Arbeitsverzeichnis
WORKDIR /var/www/html

# Öffne Port 80
EXPOSE 80

# Starte den Apache-Server
CMD ["apache2-foreground"]
