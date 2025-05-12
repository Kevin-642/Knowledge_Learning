<?php

namespace App\Form;

// Classe de base pour les formulaires Symfony
use Symfony\Component\Form\AbstractType;

// Type de champ spécifique pour les adresses email
use Symfony\Component\Form\Extension\Core\Type\EmailType;

// Permet de construire le formulaire
use Symfony\Component\Form\FormBuilderInterface;

// Pour définir les options du formulaire
use Symfony\Component\OptionsResolver\OptionsResolver;

// Contrainte de validation : champ requis
use Symfony\Component\Validator\Constraints\NotBlank;

class ResetPasswordRequestFormType extends AbstractType
{
    // Méthode qui construit le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour saisir l'email
            ->add('email', EmailType::class, [
                'attr' => ['autocomplete' => 'email'], // Aide à l’auto-complétion dans le navigateur
                'constraints' => [
                    // Le champ ne doit pas être vide
                    new NotBlank([
                        'message' => 'Veuillez entrer votre adresse email.',
                    ]),
                ],
            ])
        ;
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Aucune liaison à une entité (dans ce cas précis)
        ]);
    }
}