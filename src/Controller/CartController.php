<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    // Route pour afficher le panier de l'utilisateur
    #[Route('/cart', name: 'app_cart')]
    public function index(EntityManagerInterface $em, CartRepository $cartRepository): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère tous les éléments du panier associés à l'utilisateur
        $cartItems = $em->getRepository(Cart::class)->findBy([
            'user' => $user,
        ]);

        // Affiche la vue avec les éléments du panier
        return $this->render('cart/index.html.twig', [
            'cartItems' => $cartItems,
        ]);
    }

    // Route pour ajouter un élément au panier
    #[Route('/cart/add',name:'add_cart')]
    public function addToCart(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        // Vérifie si l'utilisateur est connecté
        $user = $security->getUser();
        if (!$user) {
            // Si l'utilisateur n'est pas connecté, redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }

        // Récupère les paramètres de l'URL (id_cursus ou id_lesson)
        $cursusId = $request->get('id_cursus');
        $lessonId = $request->get('id_lesson');

        // Recherche les entités Cursus ou Lesson dans la base de données
        $cursus = $cursusId ? $em->getRepository(Cursus::class)->find($cursusId) : null;
        $lesson = $lessonId ? $em->getRepository(Lesson::class)->find($lessonId) : null;

        // Vérifie si l'élément est déjà dans le panier de l'utilisateur
        $cartItem = $em->getRepository(Cart::class)->findOneBy([
            'user' => $user,
            'cursus' => $cursus,
            'lesson' => $lesson,
        ]);

        // Si l'élément est déjà dans le panier, affiche un message d'erreur
        if ($cartItem) {
            $this->addFlash('error', 'Cet élément est déjà dans votre panier.');
            return $this->redirectToRoute('app_formation');
        }

        // Crée un nouvel élément de panier
        $cartItem = new Cart();
        $cartItem->setUser($user)
            ->setCursus($cursus)
            ->setLesson($lesson)
            ->setCreatedAt(new \DateTimeImmutable());

        // Enregistre le nouvel élément dans la base de données
        try {
            $em->persist($cartItem);
            $em->flush();
        } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
            // Si une violation de contrainte unique se produit (élément déjà présent), affiche un message d'erreur
            $this->addFlash('error', 'Cet élément est déjà dans votre panier.');
            return $this->redirectToRoute('cart_index');
        }

        // Redirige l'utilisateur vers la page du panier après l'ajout
        return $this->redirectToRoute('app_cart');
    }

    // Route pour supprimer un élément du panier
    #[Route('cart/remove/{id_cart}', name:'remove_cart_item', methods:['POST'])]
    public function removeCartItem(int $id_cart, EntityManagerInterface $em, Security $security): Response
    {
        // Récupère l'utilisateur connecté
        $user = $security->getUser();
        if (!$user) {
            // Si l'utilisateur n'est pas connecté, affiche un message d'erreur et redirige vers la page de connexion
            $this->addFlash('error', 'Vous devez vous connecter pour effectuer cette action !');
            return $this->redirectToRoute('app_login');
        }

        // Recherche l'élément à supprimer dans le panier par son ID
        $cartItem = $em->getRepository(Cart::class)->find($id_cart);

        // Supprime l'élément trouvé
        $em->remove($cartItem);
        $em->flush();

        // Affiche un message de succès après la suppression
        $this->addFlash('success', 'L\'élément a bien été supprimé de votre panier !');

        // Redirige l'utilisateur vers la page du panier
        return $this->redirectToRoute('app_cart');
    }
}