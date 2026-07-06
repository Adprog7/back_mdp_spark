# 1. Image PHP 8.4 officielle avec Apache
FROM php:8.4-apache

# 2. On installe juste le strict minimum pour MySQL et la réécriture d'URL
RUN apt-get update && apt-get install -y unzip git curl \
    && docker-php-ext-install pdo pdo_mysql \
    && a2enmod rewrite

# 3. Apache pointe sur /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. On copie tout (le dossier vendor mis à jour en local est inclus !)
WORKDIR /var/www/html
COPY . .

# 5. Droits d'accès
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80