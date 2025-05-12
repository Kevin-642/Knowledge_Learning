<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    // Utilisation du trait ResetPasswordControllerTrait pour gérer la logique de réinitialisation de mot de passe
    use ResetPasswordControllerTrait;

    // Constructeur avec injection des dépendances nécessaires pour gérer la réinitialisation
    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper, // Interface pour la gestion des tokens de réinitialisation
        private EntityManagerInterface $entityManager // Pour interagir avec la base de données
    ) {
    }

    /**
     * Affiche et traite le formulaire pour demander une réinitialisation de mot de passe.
     */
    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        // Création du formulaire de demande de réinitialisation de mot de passe
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $email */
            $email = $form->get('email')->getData();

            // Appel de la méthode pour envoyer l'email de réinitialisation
            return $this->processSendingPasswordResetEmail($email, $mailer, $translator);
        }

        // Rendu du formulaire de demande de réinitialisation
        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form,
        ]);
    }

    /**
     * Page de confirmation après que l'utilisateur a demandé la réinitialisation de mot de passe.
     */
    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        // Générer un token factice si l'utilisateur n'existe pas ou si quelqu'un accède directement à cette page
        // Cela empêche de révéler si un utilisateur avec cet email existe ou non
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        // Affichage du message de confirmation avec le token généré
        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Valide et traite l'URL de réinitialisation que l'utilisateur a cliquée dans son email.
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher, TranslatorInterface $translator, ?string $token = null): Response
    {
        // Si un token est passé dans l'URL, on le stocke dans la session pour éviter les attaques par JavaScript
        if ($token) {
            // Stockage du token dans la session
            $this->storeTokenInSession($token);

            // Redirection vers la même page sans le token dans l'URL
            return $this->redirectToRoute('app_reset_password');
        }

        // Récupération du token depuis la session
        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            // Validation du token et récupération de l'utilisateur associé
            /** @var User $user */
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            // En cas d'erreur (par exemple, token invalide), on affiche un message d'erreur
            $this->addFlash('reset_password_error', sprintf(
                '%s - %s',
                $translator->trans('reset_password.token_invalid', [], 'ResetPasswordBundle'),
                $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            ));

            // Redirection vers la page de demande de réinitialisation de mot de passe
            return $this->redirectToRoute('app_forgot_password_request');
        }

        // Le token est valide, on permet à l'utilisateur de changer son mot de passe
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On supprime le token après utilisation (un token ne doit être utilisé qu'une seule fois)
            $this->resetPasswordHelper->removeResetRequest($token);

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Hachage du nouveau mot de passe et enregistrement dans la base de données
            $user->setPassword($passwordHasher->hashPassword($user, $plainPassword));
            $this->entityManager->flush();

            // Nettoyage de la session après réinitialisation du mot de passe
            $this->cleanSessionAfterReset();

            // Redirection vers la page d'accueil après réinitialisation du mot de passe
            return $this->redirectToRoute('app_home');
        }

        // Rendu du formulaire de réinitialisation du mot de passe
        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form,
        ]);
    }

    /**
     * Envoie l'email de réinitialisation de mot de passe.
     */
    private function processSendingPasswordResetEmail(string $email, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
    {
        // Recherche de l'utilisateur dans la base de données par son email
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($email);

        if (!$user) {
            // Si l'utilisateur n'existe pas, on génère un token factice ou on lance une erreur
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }

        // Génération du token de réinitialisation de mot de passe
        $resetToken = $this->resetPasswordHelper->generateResetToken($user);

        // Envoi de l'email de réinitialisation avec le token
        $email = (new TemplatedEmail())
            ->from(new Address('knowledge@learning.com', 'L\'équipe Knowledge Learning'))
            ->to($email)
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ]);

        // Envoi de l'email
        $mailer->send($email);

        // Stockage du token dans la session pour utilisation future
        $this->setTokenObjectInSession($resetToken);

        // Redirection vers la page de confirmation d'email
        return $this->redirectToRoute('app_check_email');
    }
}