<?php

namespace App\Controller\Admin;

use App\Entity\Cursus;
use App\Entity\Lesson;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Theme;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;


class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ThemeCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="assets/images/logo_knowledge.png" class="img-fluid d-block mx-auto" style="max-width:100px; width:100%;"><h3 class="mt-3">Knowledge Learning Admin</h3>')
            ->setFaviconPath("assets/images/logo_knowledge.png");
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Navigation');
        yield MenuItem::linktoRoute('Accueil', 'fa fa-home', 'app_home');
        yield MenuItem::section('Cours');
        yield MenuItem::linkToCrud('Theme', 'fa-solid fa-folder', Theme::class);
        yield MenuItem::linkToCrud('Cursus', 'fa-solid fa-book', Cursus::class);
        yield MenuItem::linkToCrud('Lesson', 'fa-solid fa-file-lines', Lesson::class);

    }
}
