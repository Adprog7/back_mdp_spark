# 1. Image PHP 8.4 officielle avec Apache
FROM php:8.4-apache

# 2. Installer les dépendances système et TOUTES les extensions PHP nécessaires à Laravel
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring xml bcmath \
    && a2enmod rewrite

# 3. Configurer Apache pour pointer sur le dossier /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Récupérer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Définir le répertoire de travail
WORKDIR /var/www/html

# 6. Copier le projet
COPY . .

# 7. Installer les dépendances
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# 8. Droits d'accès cruciaux pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80