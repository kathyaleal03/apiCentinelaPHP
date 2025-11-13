FROM php:8.2-apache

# 1. Instalar dependencias y drivers
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql zip

# 2. Activar Rewrite (para rutas de Laravel)
RUN a2enmod rewrite

# 3. Configurar la carpeta pública de Apache
# NOTA: Usamos el puerto 80 por defecto, no tocamos puertos aquí.
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 4. Copiar archivos del proyecto
COPY . /var/www/html

# 5. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 6. Permisos (CRÍTICO)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Exponer el puerto 80 (Informativo para Render)
EXPOSE 80