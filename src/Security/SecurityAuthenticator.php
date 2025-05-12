<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse; // Classe pour la redirection HTTP
use Symfony\Component\HttpFoundation\Request; // Classe représentant la requête HTTP
use Symfony\Component\HttpFoundation\Response; // Classe représentant la réponse HTTP
use Symfony\Component\Routing\Generator\UrlGeneratorInterface; // Interface pour générer des URLs
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface; // Interface pour le token d'authentification
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator; // Classe de base pour l'authentification via formulaire
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge; // Badge pour la protection CSRF
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge; // Badge pour la gestion "se souvenir de moi"
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge; // Badge pour l'utilisateur
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials; // Credentials pour le mot de passe
use Symfony\Component\Security\Http\Authenticator\Passport\Passport; // Classe représentant le passeport de l'utilisateur
use Symfony\Component\Security\Http\SecurityRequestAttributes; // Classe pour les attributs de la requête de sécurité
use Symfony\Component\Security\Http\Util\TargetPathTrait; // Trait pour récupérer le chemin de redirection cible

/**
 * Cette classe gère l'authentification de l'utilisateur à partir du formulaire de connexion.
 * Elle étend AbstractLoginFormAuthenticator pour fournir la logique nécessaire à l'authentification via un formulaire.
 */
class SecurityAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait; // Utilise le trait pour obtenir le chemin de redirection cible après la connexion

    // Définition de la route pour la page de connexion
    public const LOGIN_ROUTE = 'app_login';

    /**
     * Constructeur de la classe SecurityAuthenticator.
     * 
     * @param UrlGeneratorInterface $urlGenerator Service pour générer des URLs
     */
    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    /**
     * Cette méthode est appelée pour authentifier l'utilisateur.
     * Elle récupère l'email et le mot de passe depuis la requête, et crée un Passport pour l'authentification.
     * 
     * @param Request $request La requête HTTP contenant les informations du formulaire de connexion.
     * 
     * @return Passport Un objet Passport qui contient les badges nécessaires à l'authentification.
     */
    public function authenticate(Request $request): Passport
    {
        // Récupère l'email et le mot de passe du formulaire
        $email = $request->getPayload()->getString('email');
        $password = $request->getPayload()->getString('password');

        // Enregistre l'email dans la session pour l'afficher lors du prochain affichage du formulaire
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $email);

        // Crée et retourne un Passport pour l'utilisateur avec les informations récupérées
        return new Passport(
            new UserBadge($email), // Le badge pour l'email de l'utilisateur
            new PasswordCredentials($password), // Les credentials pour le mot de passe
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')), // Protection CSRF
                new RememberMeBadge(), // Badge pour gérer le "se souvenir de moi"
            ]
        );
    }

    /**
     * Cette méthode est appelée lorsque l'authentification réussit.
     * Elle gère la redirection de l'utilisateur vers la page cible ou la page d'accueil.
     * 
     * @param Request $request La requête HTTP contenant la demande de redirection.
     * @param TokenInterface $token Le token d'authentification de l'utilisateur.
     * @param string $firewallName Le nom du pare-feu de sécurité utilisé.
     * 
     * @return Response La réponse HTTP avec la redirection vers la page cible.
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Vérifie si une redirection cible existe (par exemple, une page demandée avant la connexion)
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath); // Redirige vers cette page
        }

        // Si aucune redirection cible, on redirige vers la page d'accueil
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
    }

    /**
     * Cette méthode retourne l'URL de la page de connexion.
     * 
     * @param Request $request La requête HTTP.
     * 
     * @return string L'URL de la page de connexion.
     */
    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE); // Génère l'URL de la page de connexion
    }
}