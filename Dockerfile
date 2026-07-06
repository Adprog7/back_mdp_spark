# 1. Utiliser une image Apache + PHP 8.4 taillée pour la production et Laravel
FROM webdevops/php-apache:8.4

# 2. Configurer Apache pour pointer sur le dossier /public de Laravel
ENV WEB_DOCUMENT_ROOT=/var/www/html/public

# 3. Définir le répertoire de travail
WORKDIR /var/www/html

# 4. Copier tout le code du projet
COPY . .

# 5. Installer les dépendances PHP via Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# 6. Donner les droits d'accès indispensables pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80