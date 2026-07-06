# 1. Image PHP 8.4 officielle avec Apache (pas de séparation FPM/Port 9000)
FROM php:8.4-apache

# 2. Installer les outils système essentiels
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# 3. Configurer Apache pour pointer sur le dossier /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Récupérer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Définir le répertoire de travail
WORKDIR /var/www/html

# 6. Copier tout le projet
COPY . .

# 7. Installer les dépendances sans bloquer sur les scripts
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs --no-scripts

# 8. Droits d'accès standard pour l'image officielle (www-data)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80