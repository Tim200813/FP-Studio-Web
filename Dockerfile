# Verwende das offizielle PHP-Image mit Apache
FROM php:8.1-apache

# Kopiere alle Dateien in das Apache-Verzeichnis
COPY . /var/www/html/

# Setze den Arbeitsordner
WORKDIR /var/www/html/

# Öffne Port 80
EXPOSE 80

# Starte den Apache-Server
CMD ["apache2-foreground"]
