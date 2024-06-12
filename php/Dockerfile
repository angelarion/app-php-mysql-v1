FROM php:7.4-apache

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli

# Copiar el contenido al contenedor
COPY . /var/www/html

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html
