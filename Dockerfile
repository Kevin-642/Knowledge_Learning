# Utiliser l'image PHP 8.2
FROM php:8.2-fpm

# Installer les dépendances
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql xml opcache \
    && rm -r /var/lib/apt/lists/*

# Copier le code de l'application dans le container
COPY . /var/www/symfony

# Définir le répertoire de travail
WORKDIR /var/www/symfony

# Installer les dépendances avec Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Exposer le port 9000 (ou 80 si vous avez un serveur web)
EXPOSE 9000

# Démarrer PHP-FPM
CMD ["php-fpm"]