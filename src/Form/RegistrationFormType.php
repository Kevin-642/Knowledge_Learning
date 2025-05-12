<?php

namespace App\Form;

// On importe l'entité User qui sera liée au formulaire
use App\Entity\User;

// Importation des classes nécessaires pour la création du formulaire
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Importation des contraintes de validation
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    // Construction des champs du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ pour le nom d'utilisateur
            ->add('username')

            // Champ pour l'adresse email
            ->add('email')

            // Case à cocher pour accepter les conditions générales
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false, // Non lié à l'entité User
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.', // Message si la case n’est pas cochée
                    ]),
                ],
            ])

            // Champ pour saisir le mot de passe, à entrer deux fois
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class, // Type mot de passe

                // Options pour le premier champ de saisie
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Entrer un mot de passe', // Message si vide
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de passe doit contenir un minimum de {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Password', // Libellé du champ
                ],

                // Options pour le deuxième champ de saisie (confirmation)
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => 'Confirm Password', // Libellé
                ],

                // Message si les deux champs ne sont pas identiques
                'invalid_message' => 'Les champs du mot de passe ne correspondent pas.',
                'mapped' => false, // Ce champ n’est pas directement lié à une propriété de l'entité User
            ]);
    }

    // Configuration des options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Ce formulaire est lié à l’entité User
            'data_class' => User::class,
        ]);
    }
}