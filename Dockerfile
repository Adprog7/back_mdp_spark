# 1. Utiliser une image complète et pré-configurée pour le web et Laravel
FROM webdevops/php-apache:8.3

# 2. Configurer Apache pour pointer sur le dossier /public de Laravel
ENV WEB_DOCUMENT_ROOT=/var/www/html/public

# 3. Définir le répertoire de travail
WORKDIR /var/www/html

# 4. Copier les fichiers du projet
COPY . .

# 5. Installer les dépendances en ignorant les restrictions strictes de plateforme
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# 6. Donner les droits d'accès indispensables pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# L'image écoute nativement sur le port 80
EXPOSE 80
