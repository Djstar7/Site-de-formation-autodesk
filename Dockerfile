# Étape 1 : base PHP avec toutes les extensions utiles
FROM php:8.2-cli

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    npm \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip

# Installer Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Créer le dossier de travail
WORKDIR /var/www

# Copier tous les fichiers
COPY . .

# Installer les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Installer les dépendances JS + build Vite
RUN npm install && npm run build

# Donner les bons droits à Laravel
RUN chmod -R 755 storage bootstrap/cache

# Générer la clé (facultatif si tu veux le faire manuellement)
# RUN php artisan key:generate

# Exposer le port
EXPOSE 8000

# Commande de démarrage
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
