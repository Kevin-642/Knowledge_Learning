<?php

namespace App\Controller\Admin;

// Importation des entités et classes nécessaires
use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Controller\Admin\Trait\ReadOnlyTrait;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\File;

// Contrôleur CRUD pour la gestion des entités Lesson dans l'administration
class LessonCrudController extends AbstractCrudController
{
    // Utilisation du trait pour rendre les entités en lecture seule si nécessaire
    use ReadOnlyTrait;

    // Spécifie l'entité liée à ce CRUD
    public static function getEntityFqcn(): string
    {
        return Lesson::class;
    }

    // Configuration des champs visibles/modifiables dans EasyAdmin
    public function configureFields(string $pageName): iterable
    {
        return [
            // Affiche uniquement l'ID dans la liste des leçons
            IdField::new('id_lesson')->onlyOnIndex(),

            // Nom de la leçon
            TextField::new('name_lesson'),

            // Prix de la leçon, stocké en euros (pas en centimes)
            MoneyField::new('price')
                ->setCurrency('EUR')
                ->setStoredAsCents(false),

            // Contenu détaillé (champ texte long)
            TextareaField::new('content'),

            // Champ pour uploader une vidéo (MP4 uniquement)
            ImageField::new('video_url')
                ->setUploadDir('public/assets/cursus/videos') // Dossier de stockage
                ->setBasePath('/cursus/videos') // Chemin d'accès côté web
                ->setUploadedFileNamePattern('[randomhash].[extension]') // Nom aléatoire
                ->setFileConstraints([
                    new File([
                        'maxSize' => '100M', // Taille maximale autorisée
                        'mimeTypes' => ['video/mp4'], // Format accepté
                        'mimeTypesMessage' => 'Veuillez télécharger une vidéo valide (MP4)',
                        'maxSizeMessage' => 'Veuillez télécharger une vidéo valide 100M maximum'
                    ]),
                ])
                ->setRequired(false), // Ce champ est facultatif

            // Brève description de la leçon
            TextField::new('description'),

            // Lien vers l'entité Cursus (relation ManyToOne probablement)
            AssociationField::new('cursus')
                ->setCrudController(CursusCrudController::class)
                ->setLabel('Cursus')
                ->setFormTypeOptions(['placeholder' => 'Sélectionner un cursus']),

            // Image pour la certification de la leçon
            ImageField::new('certificationImage')
                ->setUploadDir('public/assets/cursus/images/certification')
                ->setBasePath('/cursus/images/certification')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setFileConstraints([
                    new File([
                        'maxSize' => '100M',
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (jpg, jpeg, png)',
                        'maxSizeMessage' => 'Veuillez télécharger une image valide 100M maximum'
                    ]),
                ])
                ->setRequired(false),

            // Dates affichées uniquement dans la liste des leçons (non modifiables)
            DateTimeField::new('createdAt', 'Créé le')->onlyOnIndex(),
            DateTimeField::new('updatedAt', 'Mis à jour le')->onlyOnIndex(),
        ];
    }
}