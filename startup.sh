#!/bin/bash

# 1. Modifier la configuration Nginx d'Azure pour pointer vers /public
sed -i 's|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g' /etc/nginx/sites-available/default

# 2. Recharger Nginx pour appliquer
service nginx reload