FROM php:8.2-apache


# Instalar extensiones necesarias para Laravel 12
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd pdo pdo_mysql mysqli zip bcmath intl opcache


# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


# Configurar directorio de trabajo
WORKDIR /var/www/html


RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' /etc/apache2/sites-available/000-default.conf


# Crear carpetas antes de cambiar permisos
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache && \
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache


# Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite


# Exponer el puerto 80
EXPOSE 80


# Comando por defecto al iniciar el contenedor
CMD ["apache2-foreground"]


# Evitar problemas al escribir logs o cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache