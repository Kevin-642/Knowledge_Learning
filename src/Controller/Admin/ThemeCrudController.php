<?php

namespace App\Controller\Admin;

// Importation de l'entité Theme
use App\Entity\Theme;

// Importation d’un trait pour rendre les entités en lecture seule si besoin
use App\Controller\Admin\Trait\ReadOnlyTrait;

// Importation des classes nécessaires d’EasyAdmin pour créer un contrôleur CRUD
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

// Contrôleur CRUD pour gérer l'entité "Theme" dans le panneau d’administration
class ThemeCrudController extends AbstractCrudController
{
    // Intégration du trait ReadOnlyTrait (gère les accès en lecture seule)
    use ReadOnlyTrait;

    // Définition de l'entité liée à ce CRUD
    public static function getEntityFqcn(): string
    {
        return Theme::class;
    }

    // Définition des champs qui seront affichés dans les formulaires/listes
    public function configureFields(string $pageName): iterable
    {
        return [
            // Affichage de l'ID (lecture seule, visible uniquement dans la liste)
            IdField::new('id_theme')->onlyOnIndex(),

            // Nom du thème, champ texte modifiable
            TextField::new('name_theme'),
        ];
    }
}