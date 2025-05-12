<?php

namespace App\Controller\Admin;

// Importation des entités et classes nécessaires
use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Entity\Theme;
use App\Controller\Admin\ThemeCrudController;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// Contrôleur principal du tableau de bord d'administration
class DashboardController extends AbstractDashboardController
{
    // Route principale vers le back-office (URL : /admin)
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Génération d'une URL vers le contrôleur d'administration des thèmes
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        
        // Redirection automatique vers la page d'administration des thèmes
        return $this->redirect($adminUrlGenerator->setController(ThemeCrudController::class)->generateUrl());
    }

    // Configuration du tableau de bord principal (titre, favicon, etc.)
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle(
                // Titre personnalisé avec logo et nom de l'application
                '<img src="assets/images/Logo.png" class="img-fluid d-block mx-auto" style="max-width:100px; width:100%;">' .
                '<h3 class="mt-3">Knowledge Learning Admin</h3>'
            )
            ->setFaviconPath("assets/images/Logo.png"); // Définition du favicon
    }

    // Configuration du menu de navigation dans l'administration
    public function configureMenuItems(): iterable
    {
        // Section de navigation générale
        yield MenuItem::section('Navigation');
        yield MenuItem::linktoRoute('Accueil', 'fa fa-home', 'app_home'); // Lien vers l'accueil du site

        // Section liée à la gestion des cours
        yield MenuItem::section('Cours');
        yield MenuItem::linkToCrud('Theme', 'fa-solid fa-folder', Theme::class); // Lien vers l'administration des thèmes
        yield MenuItem::linkToCrud('Cursus', 'fa-solid fa-book', Cursus::class); // Lien vers l'administration des cursus
        yield MenuItem::linkToCrud('Lesson', 'fa-solid fa-file-lines', Lesson::class); // Lien vers l'administration des leçons
    }
}