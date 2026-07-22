# 1. Usar PHP 8.3 con Apache (requerido por tu proyecto)
FROM php:8.3-apache

# 2. Instalar dependencias del sistema e incluir soporte para PostgreSQL (libpq-dev)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    curl \
    && docker-php-ext-install pdo_pgsql pgsql pdo_mysql mbstring exif pcntl bcmath gd

# 3. Habilitar mod_rewrite de Apache
RUN a2enmod rewrite

# 4. Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Definir el directorio de trabajo
WORKDIR /var/www/html

# 6. Copiar el proyecto
COPY . .

# 7. Ajustar el DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# 8. Instalar dependencias omitiendo la verificación de la extensión ext-mongodb
RUN composer install --no-dev --optimize-autoloader --ignore-platform-req=ext-mongodb

# 9. Ajustar permisos de storage y cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]
