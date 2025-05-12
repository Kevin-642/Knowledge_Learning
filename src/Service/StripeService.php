<?php

namespace App\Service;

use Stripe\Checkout\Session; // Importation de la classe Session de Stripe pour gérer les sessions de paiement
use Stripe\Stripe; // Importation de la classe Stripe pour l'initialisation avec la clé API

/**
 * Ce service permet de gérer les paiements via Stripe, en particulier la création de sessions de paiement.
 * Il interagit avec l'API Stripe pour configurer et créer une session de paiement pour un panier d'articles.
 */
class StripeService
{
    // Clé secrète Stripe, utilisée pour interagir avec l'API
    private $stripeSecretKey;

    /**
     * Constructeur du service StripeService.
     * 
     * @param string $stripeSecretKey La clé secrète de l'API Stripe pour l'authentification.
     */
    public function __construct(string $stripeSecretKey)
    {
        $this->stripeSecretKey = $stripeSecretKey;
    }

    /**
     * Cette méthode crée une session de paiement Stripe Checkout.
     * Elle prend les éléments du panier, l'URL de succès et l'URL d'annulation.
     * 
     * @param array $cartItems Les éléments du panier de l'utilisateur.
     * @param string $successUrl L'URL vers laquelle l'utilisateur est redirigé après une transaction réussie.
     * @param string $cancelUrl L'URL vers laquelle l'utilisateur est redirigé si le paiement est annulé.
     * 
     * @return Session La session de paiement créée, qui contient un lien de redirection vers Stripe Checkout.
     */
    public function createCheckoutSession(array $cartItems, string $successUrl, string $cancelUrl): Session
    {
        // Initialise la clé API de Stripe
        Stripe::setApiKey($this->stripeSecretKey);

        // Tableau pour stocker les éléments de ligne à envoyer à Stripe
        $lineItems = [];

        // Parcours des articles dans le panier pour les ajouter à la session de paiement
        foreach ($cartItems as $item) {
            // Détermine le nom du cursus ou de la leçon à partir de l'objet de l'article
            $name = $item->getCursus() ? $item->getCursus()->getNameCursus() : $item->getLesson()->getNameLesson();
            // Détermine le prix du cursus ou de la leçon
            $price = $item->getCursus() ? $item->getCursus()->getPrice() : $item->getLesson()->getPrice();
            // La quantité de l'élément dans le panier (par défaut à 1)
            $quantity = 1;

            // Ajoute cet élément au tableau des éléments de ligne à envoyer à Stripe
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'eur', // Devise utilisée pour le paiement
                    'product_data' => [
                        'name' => $name // Nom du produit (cursus ou leçon)
                    ],
                    'unit_amount' => $price * 100, // Prix en centimes (Stripe attend des montants en centimes)
                ],
                'quantity' => $quantity, // Quantité de cet article
            ];
        }

        // Crée et retourne la session de paiement Stripe Checkout
        return Session::create([
            'payment_method_types' => ['card'], // Méthode de paiement (cartes de crédit/débit)
            'line_items' => $lineItems, // Les éléments du panier
            'mode' => 'payment', // Mode de la session (ici, paiement direct)
            'success_url' => $successUrl, // URL de redirection en cas de succès
            'cancel_url' => $cancelUrl, // URL de redirection en cas d'annulation
        ]);
    }
}