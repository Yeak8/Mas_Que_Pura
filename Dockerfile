# Usa una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias necesarias para compilar extensiones
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libmysqlclient-dev \
    && rm -rf /var/lib/apt/lists/*

# Copia tu proyecto al contenedor
COPY . /var/www/html/

# Habilita mod_rewrite si usas .htaccess
RUN a2enmod rewrite

# Instalar extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql mbstring

# Da permisos necesarios
RUN chown -R www-data:www-data /var/www/html/

# Expón el puerto por el que Apache sirve (Render lo detecta automáticamente)
EXPOSE 80
