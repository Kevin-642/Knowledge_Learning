<?php

namespace App\Controller\Admin;

// Importation des classes nécessaires
use App\Entity\Cursus;
use App\Controller\Admin\Trait\ReadOnlyTrait;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\File;

// Contrôleur CRUD (Create, Read, Update, Delete) pour l'entité "Cursus" dans l'administration
class CursusCrudController extends AbstractCrudController
{
    // Utilisation d'un trait permettant de rendre les entités en lecture seule
    use ReadOnlyTrait;

    // Méthode statique retournant la classe de l'entité gérée par ce contrôleur
    public static function getEntityFqcn(): string
    {
        return Cursus::class;
    }

    // Méthode permettant de configurer les champs affichés dans le back-office selon la page (index, détail, formulaire, etc.)
    public function configureFields(string $pageName): iterable
    {
        return [
            // Affichage de l'ID uniquement dans la liste
            IdField::new('id_cursus')->onlyOnIndex(),

            // Champ texte pour le nom du cursus
            TextField::new('name_cursus'),

            // Champ monétaire pour le prix, en euros, stocké en valeur entière (pas en centimes)
            MoneyField::new('price')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            // Champ relationnel vers l'entité "Theme"
            AssociationField::new('theme')
                ->setCrudController(ThemeCrudController::class)
                ->setLabel('Thème')
                ->setFormTypeOptions(['placeholder' => 'Sélectionner un thème']),

            // Champ image pour uploader une image liée au cursus
            ImageField::new('images')
                ->setUploadDir('public/assets/cursus/images') // Dossier de destination sur le serveur
                ->setBasePath('/cursus/images') // Chemin d’accès depuis le navigateur
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Nom du fichier généré automatiquement
                ->setFileConstraints([
                    new File([
                        'maxSize' => '100M', // Taille maximale autorisée
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpg, jpeg, png)',
                        'maxSizeMessage' => 'Veuillez télécharger une image valide (100M maximum)',
                    ]),
                ])
                ->setRequired(false), // Le champ image est facultatif

            // Champ texte pour la description du cursus
            TextField::new('description'),

            // Champ collection pour afficher les leçons associées (non modifiable ici)
            CollectionField::new('lesson')
                ->hideWhenCreating() // Masqué lors de la création
                ->hideWhenUpdating(), // Masqué lors de la mise à jour

            // Date de création affichée en lecture seule
            DateTimeField::new('createdAt', 'Créé le')
                ->hideOnForm()
                ->hideWhenCreating(),

            // Date de dernière mise à jour affichée en lecture seule
            DateTimeField::new('updatedAt', 'Mis à jour le')
                ->hideOnForm()
                ->hideWhenCreating(),
        ];
    }
}