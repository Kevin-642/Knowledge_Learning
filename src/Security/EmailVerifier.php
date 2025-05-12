<?php

namespace App\Security;

use App\Entity\User; // Entité User pour l'utilisateur
use Doctrine\ORM\EntityManagerInterface; // Interface pour interagir avec la base de données
use Symfony\Bridge\Twig\Mime\TemplatedEmail; // Classe pour gérer l'email avec un template Twig
use Symfony\Component\HttpFoundation\Request; // Classe représentant la requête HTTP
use Symfony\Component\Mailer\MailerInterface; // Interface pour l'envoi d'email
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface; // Exception pour les erreurs de vérification d'email
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface; // Interface pour aider à la vérification d'email

/**
 * Cette classe gère l'envoi de l'email de confirmation de l'adresse email de l'utilisateur 
 * et la gestion de la vérification de cet email.
 */
class EmailVerifier
{
    /**
     * Constructeur de la classe EmailVerifier.
     *
     * @param VerifyEmailHelperInterface $verifyEmailHelper Aide à générer et valider les signatures d'email.
     * @param MailerInterface $mailer Service pour l'envoi des emails.
     * @param EntityManagerInterface $entityManager Permet de persister et mettre à jour l'entité User.
     */
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Envoie un email de confirmation à l'utilisateur avec un lien pour vérifier son email.
     *
     * @param string $verifyEmailRouteName Le nom de la route pour la vérification de l'email.
     * @param User $user L'utilisateur pour lequel l'email de confirmation doit être envoyé.
     * @param TemplatedEmail $email L'email contenant le template et les informations à envoyer.
     */
    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        // Génère une signature URL pour la vérification de l'email
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName, // Nom de la route de vérification
            (string) $user->getId(), // ID de l'utilisateur
            (string) $user->getEmail() // Email de l'utilisateur
        );

        // Récupère le contexte de l'email et ajoute les informations nécessaires pour le lien de vérification
        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl(); // URL signée pour la vérification
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey(); // Message de délai d'expiration
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData(); // Données d'expiration

        // Met à jour le contexte de l'email
        $email->context($context);

        // Envoie l'email
        $this->mailer->send($email);
    }

    /**
     * Valide la confirmation de l'email lorsque l'utilisateur clique sur le lien de confirmation.
     *
     * @param Request $request La requête HTTP contenant les paramètres de la vérification de l'email.
     * @param User $user L'utilisateur dont l'email doit être vérifié.
     * 
     * @throws VerifyEmailExceptionInterface Si l'email de l'utilisateur ne peut pas être vérifié.
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        // Valide la confirmation de l'email depuis la requête HTTP
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, (string) $user->getId(), (string) $user->getEmail());

        // Si la validation réussit, on marque l'utilisateur comme vérifié
        $user->setVerified(true);

        // Persiste l'utilisateur mis à jour et enregistre les modifications dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}