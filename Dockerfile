# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Copia tu proyecto al contenedor
COPY . /var/www/html/

# (Opcional) Habilita mod_rewrite si usas .htaccess
RUN a2enmod rewrite

# Expón el puerto por el que Apache sirve (Render lo detecta automáticamente)
EXPOSE 80


# Copia el código fuente al contenedor
COPY . /var/www/html/

# Habilita extensiones necesarias (por ejemplo mysqli)
RUN docker-php-ext-install mysqli

# Da permisos necesarios
RUN chown -R www-data:www-data /var/www/html

RUN docker-php-ext-install pdo pdo_mysql mbstring
