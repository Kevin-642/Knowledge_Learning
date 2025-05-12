<?php

// Charge la classe Dotenv, qui est utilisée pour charger les variables d'environnement à partir d'un fichier .env
use Symfony\Component\Dotenv\Dotenv;

// Charge automatiquement les fichiers nécessaires via Composer (autoloader)
require dirname(__DIR__).'/vendor/autoload.php';

// Vérifie si le fichier bootstrap.php existe dans le répertoire config
if (file_exists(dirname(__DIR__).'/config/bootstrap.php')) {
    // Si le fichier bootstrap.php existe, il est inclus pour initialiser l'application
    require dirname(__DIR__).'/config/bootstrap.php';
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    // Si le fichier bootstrap.php n'existe pas et que la méthode bootEnv est présente dans la classe Dotenv,
    // cette méthode est appelée pour charger les variables d'environnement depuis le fichier .env
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}