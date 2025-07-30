# Utiliser une image PHP avec Apache
FROM php:8.3-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Copier les fichiers Laravel
COPY . /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Aller dans le dossier Laravel
WORKDIR /var/www/html

# Installer les dépendances Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Activer mod_rewrite
RUN a2enmod rewrite

# Activer les routes Laravel
COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf
