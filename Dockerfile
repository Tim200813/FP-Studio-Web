# Verwende das offizielle PHP-Image mit Apache
FROM php:8.1-apache

# Setze ServerName für Apache (verhindert die Fehlermeldung)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Setze index.php als Standard-Startseite
RUN echo "DirectoryIndex index.php index.html" > /etc/apache2/mods-enabled/dir.conf

# Kopiere alle Dateien in das Apache-Verzeichnis
COPY . /var/www/html/

# Setze den Arbeitsordner
WORKDIR /var/www/html/

# Öffne Port 80
EXPOSE 80

# Starte den Apache-Server
CMD ["apache2-foreground"]
