# Utiliser l'image officielle PHP avec Apache
FROM php:8.2-apache

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev zip libzip-dev npm \
    && docker-php-ext-install pdo_mysql zip

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet dans le conteneur
COPY . /var/www/html

# Installer les dépendances Laravel
WORKDIR /var/www/html
RUN composer install
RUN npm install && npm run build

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Activer le rewrite module
RUN a2enmod rewrite

# Exposer le port 80
EXPOSE 80
