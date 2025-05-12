<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Repository\CartRepository;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CheckoutController extends AbstractController
{
    // Route pour créer une session de paiement avec Stripe
    #[Route('/create-checkout-session', name: 'app_checkout')]
    public function createCheckoutSession(Request $request, CartRepository $cartRepository, StripeService $stripeService, EntityManagerInterface $em): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère les éléments du panier de l'utilisateur
        $cartItems = $cartRepository->findBy(['user' => $user]);

        // Crée une session de paiement Stripe en utilisant les éléments du panier
        $session = $stripeService->createCheckoutSession(
            $cartItems, // Les éléments du panier
            $this->generateUrl('success_payment', [], UrlGeneratorInterface::ABSOLUTE_URL), // URL de succès
            $this->generateUrl('cancel_payment', [], UrlGeneratorInterface::ABSOLUTE_URL), // URL d'annulation
        );

        // Redirige l'utilisateur vers la page de paiement Stripe
        return $this->redirect($session->url);
    }

    // Route appelée après un paiement réussi
    #[Route('/success_payment', name: 'success_payment')]
    public function successPayment(CartRepository $cartRepository, EntityManagerInterface $em): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère les éléments du panier de l'utilisateur
        $cartItems = $cartRepository->findBy(['user' => $user]);

        // Pour chaque élément du panier, crée un enregistrement de la commande dans la base de données
        foreach ($cartItems as $item) { 
            $purchase = new Purchase(); // Crée une nouvelle commande (purchase)
            $purchase->setUser($user); // Associe la commande à l'utilisateur
            $purchase->setPurchaseAt(new \DateTimeImmutable()); // Définit la date de l'achat

            // Associe le cursus ou la leçon à la commande, selon ce qui est dans le panier
            if ($item->getCursus()) {
                $purchase->setCursus($item->getCursus());
            } elseif ($item->getLesson()) {
                $purchase->setLesson($item->getLesson());
            }

            // Enregistre la commande en base de données
            $em->persist($purchase);

            // Supprime l'élément du panier après l'achat
            $em->remove($item);
        }

        // Valide les changements dans la base de données
        $em->flush();

        // Rendu de la vue de succès de paiement
        return $this->render('checkout/success_payment.html.twig');
    }

    // Route appelée après un paiement annulé
    #[Route('/cancel_payment', name: 'cancel_payment')]
    public function cancelPayment(): Response
    {
        // Rendu de la vue d'annulation de paiement
        return $this->render('checkout/cancel_payment.html.twig');
    }
}