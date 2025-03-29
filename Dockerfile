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

# Étape pour installer Composer
RUN curl -sS https://getcomposer.org/installer | php --install-dir=/usr/local/bin --filename=composer

# Étape pour installer Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Installation des dépendances
RUN composer install --no-dev --optimize-autoloader --no-scripts


# Exposer le port (ajuste selon ton besoin)
EXPOSE 9000


# Démarrer PHP-FPM
CMD ["php-fpm"]