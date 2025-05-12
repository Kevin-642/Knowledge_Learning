<?php

namespace App\Controller\Admin\Trait;

// Importation des classes EasyAdmin nécessaires à la configuration des actions
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

// Trait destiné à être utilisé dans des contrôleurs pour limiter les actions disponibles
trait ReadOnlyTrait
{
    /**
     * Cette méthode surcharge la configuration des actions dans EasyAdmin.
     * Elle permet de :
     * - Supprimer les actions "Nouveau", "Modifier" et "Supprimer"
     * - Ajouter uniquement l’action "Détail" sur la page d’index
     */
    public function configureActions(Actions $actions): Actions
    {
        // On ajoute uniquement l'action "Détail" sur la page d’index
        $actions->add(Crud::PAGE_INDEX, Action::DETAIL);

        // Retourne l'ensemble des actions configurées (dans ce cas, lecture seule)
        return $actions;
    }
}