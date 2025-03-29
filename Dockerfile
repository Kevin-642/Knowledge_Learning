# Choisir l'image PHP officielle
FROM php:8.1-cli

# Installer les dépendances nécessaires pour Symfony
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libicu-dev \
    zlib1g-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd intl zip opcache \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copier les fichiers de ton projet dans le conteneur
COPY . /app

# Définir le répertoire de travail
WORKDIR /app

# Installer les dépendances PHP avec Composer
RUN composer install --no-dev --optimize-autoloader

# Commande pour démarrer le serveur Symfony (ou un serveur web comme Nginx si tu préfères)
CMD ["php", "bin/console", "server:run", "0.0.0.0:10000"]
