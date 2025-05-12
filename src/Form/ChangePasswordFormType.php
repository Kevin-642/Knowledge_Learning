<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
// Type de champ pour les mots de passe
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
// Type spécial pour demander deux fois le même champ (ex : mot de passe + confirmation)
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Contraintes de validation du mot de passe
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordFormType extends AbstractType
{
    // Méthode pour construire le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Ajout d’un champ de mot de passe répété (saisie + confirmation)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class, // Champ de type mot de passe
                'options' => [
                    'attr' => [
                        // Pour désactiver l’auto-complétion du navigateur
                        'autocomplete' => 'new-password',
                    ],
                ],
                'first_options' => [
                    // Contraintes appliquées au premier champ de mot de passe
                    'constraints' => [
                        new NotBlank([
                            // Message si le champ est vide
                            'message' => 'Veuillez entrer votre mot de passe, s’il vous plaît.',
                        ]),
                        new Length([
                            // Mot de passe d’au moins 12 caractères
                            'min' => 12,
                            'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères.',
                            // Longueur maximale autorisée (sécurité Symfony)
                            'max' => 4096,
                        ]),
                        new NotCompromisedPassword([
                            // Vérifie que le mot de passe n’a pas été compromis dans une fuite de données
                            'message' => 'Ce mot de passe a été divulgué dans une fuite de données, veuillez en utiliser un autre.',
                        ]),
                        new Regex([
                            // Mot de passe complexe : minuscule, majuscule, chiffre, caractère spécial
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
                            'message' => 'Votre mot de passe doit contenir une majuscule, une minuscule, un chiffre et un caractère spécial.',
                        ]),
                    ],
                    // Libellé du premier champ
                    'label' => 'Nouveau mot de passe',
                ],
                'second_options' => [
                    // Libellé du champ de confirmation
                    'label' => 'Confirmez le mot de passe',
                ],
                // Message affiché si les deux mots de passe ne correspondent pas
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                // Ce champ n’est pas directement mappé à une propriété de l’entité
                'mapped' => false,
            ])
        ;
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // Aucune option particulière définie ici
        $resolver->setDefaults([]);
    }
}