# 1. Utiliser l'image web complète pré-configurée
FROM webdevops/php-apache:8.3

# 2. Configurer Apache pour pointer sur le dossier /public de Laravel
ENV WEB_DOCUMENT_ROOT=/var/www/html/public

# 3. Définir le répertoire de travail
WORKDIR /var/www/html

# 4. Copier l'intégralité des fichiers du projet
COPY . .

# 5. Donner les droits d'accès indispensables pour Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# L'image écoute sur le port 80
EXPOSE 80
