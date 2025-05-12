<?php

namespace App\Controller;

use App\Repository\CertificationRepository;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CertificationController extends AbstractController
{
    // Route pour afficher les certifications
    #[Route('/certifications', name: 'app_certifications')]
    public function index(CertificationRepository $certificationRepository, ThemeRepository $themeRepository): Response
    {
        // Récupère l'utilisateur connecté
        $user = $this->getUser();

        // Récupère toutes les certifications de l'utilisateur depuis la base de données
        $certifications = $certificationRepository->findBy(['user'=>$user]);

        // Récupère tous les thèmes depuis la base de données
        $themes = $themeRepository->findAll();

        $data = [];

        // Parcourt chaque thème
        foreach ($themes as $theme) {
            $cursusList = [];
            
            // Pour chaque thème, on récupère les cursus associés
            foreach ($theme->getCursuses() as $cursus) {
                $lessonsList = [];

                // Pour chaque cursus, on parcourt les leçons associées
                foreach ($cursus->getLesson() as $lesson) {
                    
                    // Vérifie si l'utilisateur a obtenu une certification pour cette leçon
                    foreach ($certifications as $certification) {
                        if ($certification->getLesson() === $lesson) {
                            $lessonsList[] = [
                                'lesson' => $lesson,
                                'obtainedAt' => $certification->getObtainedAt(),
                            ];
                        }
                    }
                }
                
                // Si des leçons ont été obtenues dans ce cursus, on les ajoute à la liste des cursus
                if (!empty($lessonsList)) {
                    $cursusList[] = [
                        'cursus' => $cursus,
                        'lessonsList' => $lessonsList
                    ];
                }
            }
            
            // Si le cursusList n'est pas vide (il y a des leçons certifiées), on ajoute ce thème à la liste des données
            if (!empty($cursusList)) {
                $data[] = [
                    'theme' => $theme,
                    'cursusList' => $cursusList,
                ];
            }
        }

        // Rendu de la vue avec les données des certifications obtenues
        return $this->render('certification/index.html.twig', [
            'certifications' => $data
        ]);
    }
}