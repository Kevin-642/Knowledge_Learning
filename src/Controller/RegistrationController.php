<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    // Constructeur qui injecte la dépendance EmailVerifier
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    // Route pour l'inscription des utilisateurs
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // Création d'un nouvel utilisateur
        $user = new User();
        // Création du formulaire d'inscription
        $form = $this->createForm(RegistrationFormType::class, $user);
        // Traitement des données soumises par le formulaire
        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du mot de passe en texte clair
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // Hachage du mot de passe
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Enregistrement de l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi d'un email de confirmation d'email à l'utilisateur
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('knowledge@learning.com', 'L\'Equipe knowledge'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig') // Template HTML de l'email
            );

            // Redirection vers la page de connexion après l'inscription réussie
            return $this->redirectToRoute('app_login');
        }

        // Affichage du formulaire d'inscription
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    // Route pour la vérification de l'email
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        // Empêche l'accès à cette page si l'utilisateur n'est pas authentifié
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Validation du lien de confirmation d'email
        try {
            /** @var User $user */
            $user = $this->getUser();
            // Gestion de la confirmation de l'email
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            // Si une erreur se produit (par exemple, un lien invalide), affichage d'un message d'erreur
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            // Redirection vers la page d'inscription en cas d'erreur
            return $this->redirectToRoute('app_register');
        }

        // Message de succès si l'email a été vérifié avec succès
        $this->addFlash('success', 'Your email address has been verified.');

        // Redirection vers la page d'inscription après la vérification
        return $this->redirectToRoute('app_register');
    }
}