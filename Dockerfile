# Utiliser une image PHP avec Apache
FROM php:8.3-apache

# Installer les extensions PHP nécessaires (PostgreSQL au lieu de MySQL)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Copier les fichiers Laravel
COPY . /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Aller dans le dossier Laravel
WORKDIR /var/www/html

# Installer les dépendances Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Activer mod_rewrite
RUN a2enmod rewrite

# Copier la configuration Apache
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

# Exposer le port 80
EXPOSE 80

# Créer un script de démarrage inline
RUN echo '#!/bin/bash\n\
echo "🚀 Démarrage de Laravel..."\n\
php artisan migrate --force || echo "Migration échouée"\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
echo "✅ Démarrage Apache..."\n\
exec apache2-foreground' > /start.sh \
    && chmod +x /start.sh

# Démarrer avec le script
CMD ["/start.sh"]