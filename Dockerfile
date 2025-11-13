FROM php:8.2-apache

# 1. Instalar dependencias del sistema (Agregamos más librerías comunes para evitar errores)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev

# 2. Instalar extensiones de PHP requeridas por Laravel
RUN docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# 3. Activar Rewrite de Apache
RUN a2enmod rewrite

# 4. Configurar la carpeta pública de Apache (Puerto 80)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 5. Copiar archivos del proyecto
COPY . /var/www/html

# 6. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Instalar dependencias de Laravel (CORRECCIÓN AQUÍ)
# Agregamos --no-scripts para que no intente correr comandos de artisan sin base de datos
# Agregamos --ignore-platform-reqs por si hay alguna librería que se queje de la versión
RUN composer install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs

# 8. Permisos (CRÍTICO)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Exponer puerto 80
EXPOSE 80