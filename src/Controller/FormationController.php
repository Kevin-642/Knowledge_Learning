<?php

namespace App\Controller;

use App\Entity\Certification;
use App\Repository\CertificationRepository;
use App\Repository\LessonRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FormationController extends AbstractController
{
    // Route principale pour afficher les formations
    #[Route('/formation', name: 'app_formation')]
    public function index(ThemeRepository $themeRepository, PurchaseRepository $purchaseRepository ): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        $themes = $themeRepository->findAll(); // Récupère tous les thèmes

        // Récupération des cursus et des leçons achetés par l'utilisateur
        $purchasedCursus = [];
        $purchasedLessons = [];

        // Récupère toutes les commandes d'achats de l'utilisateur
        $purchases = $purchaseRepository->findBy(['user'=>$user]);
        foreach ($purchases as $purchase) {
            if ($purchase->getCursus()) {
                $purchasedCursus[] = $purchase->getCursus(); // Ajoute le cursus si acheté
            }
            if ($purchase->getLesson()) {
                $purchasedLessons[] = $purchase->getLesson(); // Ajoute la leçon si achetée
            }
        }

        // Logique pour éviter qu'un utilisateur achète un cursus si il a déjà toutes les leçons
        $cursusWithAllLessonsPurchased = [];
        $cursusWithSomeLessonsPurchased = [];
        foreach ($themes as $theme) {
            foreach ($theme->getCursuses() as $cursus) {
                $allLessonsPurchased = true;
                $someLessonsPurchased = false;
                foreach ($cursus->getLesson() as $lesson) {
                    if (!in_array($lesson, $purchasedLessons)) {
                        $allLessonsPurchased = false; // Si une leçon n'est pas achetée, tout le cursus ne l'est pas
                    } else {
                        $someLessonsPurchased = true; // Si au moins une leçon est achetée, on marque le cursus comme partiellement acheté
                    }
                }
                if ($allLessonsPurchased) {
                    $cursusWithAllLessonsPurchased[] = $cursus; // Cursus entièrement acheté
                }
                if ($someLessonsPurchased) {
                    $cursusWithSomeLessonsPurchased[] = $cursus; // Cursus partiellement acheté
                }
            }
        }

        // Rendu de la vue avec toutes les données
        return $this->render('formation/index.html.twig', [
            'themes' => $themes,
            'purchasedCursus' => $purchasedCursus,
            'purchasedLessons' => $purchasedLessons,
            'cursusWithAllLessonsPurchased' => $cursusWithAllLessonsPurchased,
            'cursusWithSomeLessonsPurchased' => $cursusWithSomeLessonsPurchased,
        ]);
    }

    // Route pour afficher le détail d'une leçon d'un cursus spécifique
    #[Route('/formation/cursus/lesson/{id_lesson}', name: 'app_cursus', requirements: ['id_lesson' => '\d+'])]
    public function detailLesson(int $id_lesson, LessonRepository $lessonRepository, PurchaseRepository $purchaseRepository, CertificationRepository $certificationRepository)
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        $lesson = $lessonRepository->find($id_lesson); // Récupère la leçon par son ID

        // Si la leçon n'existe pas, on génère une exception
        if (!$lesson) {
            throw $this->createNotFoundException('La leçon n\'existe pas');
        }

        // Vérifie si l'utilisateur a acheté la leçon
        $purchase = $purchaseRepository->findOneBy(['user' => $user, 'lesson' => $lesson]);
        if (!$purchase) {
            // Vérifie si l'utilisateur a acheté un cursus contenant cette leçon
            $cursusPurchase = $purchaseRepository->findBy(['user' => $user, 'cursus' => $lesson->getCursus()]);
            if (empty($cursusPurchase)) {
                $this->addFlash('error', 'Vous n\'avez pas acheté cette leçon.');
                return $this->redirectToRoute('app_formation'); // Redirige si la leçon n'est pas achetée
            }
        }

        // Vérifie si l'utilisateur a la certification de la leçon
        $certification = $certificationRepository->findOneBy(['user' => $user, 'lesson' => $lesson]);

        // Rendu de la vue avec les informations de la leçon et la certification
        return $this->render('formation/detailLesson.html.twig', [
            'lesson' => $lesson,
            'certification' => $certification,
        ]);
    }

    // Route pour valider une leçon et attribuer une certification
    #[Route('/formation/cursus/lesson/{id_lesson}/validate', name: 'validate_lesson', methods: ['POST'])]
    public function validateLesson(int $id_lesson, LessonRepository $lessonRepository, CertificationRepository $certificationRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur connecté
        $lesson = $lessonRepository->find($id_lesson); // Récupère la leçon par son ID

        // Recherche de la certification existante pour cette leçon
        $certification = $certificationRepository->findOneBy(['user' => $user, 'lesson' => $lesson]);
        if (!$certification) {
            // Si aucune certification n'existe, on en crée une nouvelle
            $certification = new Certification();
            $certification->setUser($user);
            $certification->setLesson($lesson);
            $certification->setObtainedAt(new \DateTimeImmutable()); // Date de l'obtention
            $em->persist($certification);
            $em->flush(); // Enregistre la certification
        }

        // Message flash pour indiquer que la validation a été réussie
        $this->addFlash('success', 'Félicitations, vous venez de valider une leçon. Vous avez obtenu une certification!');

        // Redirige vers la page du cursus
        return $this->redirectToRoute('app_cursus', ['id_lesson' => $id_lesson]);
    }
}