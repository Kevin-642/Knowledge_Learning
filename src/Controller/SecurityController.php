<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà authentifié, redirige-le vers la page d'accueil
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // Obtenez l'erreur d'authentification s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur entré par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Symfony interceptera cette méthode et effectuera la déconnexion
    }
}