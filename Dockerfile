# 1. Utiliser une image PHP officielle avec Apache préinstallé
FROM php:8.3-apache

# 2. Installer les extensions PHP requises pour Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && a2enmod rewrite

# 3. Configurer Apache pour pointer sur le dossier /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Définir le répertoire de travail
WORKDIR /var/www/html

# 6. Copier le projet
COPY . .

# 7. Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

# 8. Droits d'accès
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Render utilise le port 80 par défaut pour Apache
EXPOSE 80
