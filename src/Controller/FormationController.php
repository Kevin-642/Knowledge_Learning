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
    #[Route('/formation', name: 'app_formation')]
    public function index(ThemeRepository $themeRepository, PurchaseRepository $purchaseRepository ): Response
    {
        $user = $this->getUser();
        $themes = $themeRepository -> findAll();

        //Récupération des cours et des lessons acheter par l'utilisateur
        $purchasedCursus = [];
        $purchasedLessons = [];

        $purchases = $purchaseRepository->findBy(['user'=>$user]);
        foreach($purchases as $purchase){
            if($purchase->getCursus()){
                $purchasedCursus[] = $purchase->getCursus();
            }
            if($purchase->getLesson()){
                $purchasedLessons[] = $purchase->getLesson();
            }
        }

        //Logique afin d'evité qu'un utilisateur achete un cursus alor qu'il à deja les lessons
            // Vérifie si l'utilisateur possède déjà toutes les leçons d'un cours
            $cursusWithAllLessonsPurchased = [];
            // Vérifie si l'utilisateur possède une leçon dans un cours
            $cursusWithSomeLessonsPurchased = [];
            foreach ($themes as $theme) {
                foreach ($theme->getCursuses() as $cursus) {
                     $allLessonsPurchased = true;
                     $someLessonsPurchased = false;
                    foreach ($cursus->getLesson() as $lesson) {
                        if (!in_array($lesson, $purchasedLessons)) {
                            $allLessonsPurchased = false;
                        } else {
                            $someLessonsPurchased = true;
                        }
                        }
                    if ($allLessonsPurchased) {
                        $cursusWithAllLessonsPurchased[] = $cursus;
                    }
                    if($someLessonsPurchased) {
                        $cursusWithSomeLessonsPurchased [] = $cursus;
                    }
                    }
                }

        return $this->render('formation/index.html.twig', [
            'themes'=>$themes,
            'purchasedCursus'=>$purchasedCursus,
            'purchasedLessons'=>$purchasedLessons,
            'cursusWithAllLessonsPurchased' => $cursusWithAllLessonsPurchased,
            'cursusWithSomeLessonsPurchased' => $cursusWithSomeLessonsPurchased,
        ]);
    }

    #[Route('/formation/cursus/lesson/{id_lesson}', name:'app_cursus', requirements:['id_lesson' => '\d+'])]
    public function detailLesson(int $id_lesson,LessonRepository $lessonRepository, PurchaseRepository $purchaseRepository, CertificationRepository $certificationRepository)
    {
        $user = $this->getUser();
        $lesson = $lessonRepository->find($id_lesson);

        if(!$lesson){
            throw $this->createNotFoundException('La leçon n\'existe pas' );
        }
        //Vérification de l'achat de la lecon par l'utilisateur
        $purchase = $purchaseRepository->findOneBy(['user'=>$user, 'lesson'=>$lesson]);
        if (!$purchase) {
            //Vérification de l'achat d'une lecon par le bier du cursus
            $cursusPurchase = $purchaseRepository->findBy(['user'=>$user, 'cursus'=>$lesson->getCursus()]);
            if(empty($cursusPurchase)){
                $this->addFlash('error', 'Vous n\'avez pas acheter cette leçon.');
               return $this->redirectToRoute('app_formation'); 
            }
            
        }

        //Verifie si l'utilisateur a la certification de la leçon

        $certification = $certificationRepository->findOneBy(['user'=>$user, 'lesson'=>$lesson]);
        return $this->render('formation/detailLesson.html.twig',[
            'lesson'=> $lesson,
            'certification'=>$certification,
        ]);
    }

    #[Route('/formation/cursus/lesson/{id_lesson}/validate', name:'validate_lesson', methods:['POST'])]
    public function validateLesson(int $id_lesson, LessonRepository $lessonRepository, CertificationRepository $certificationRepository, EntityManagerInterface $em):Response
    {
        $user = $this->getUser();
        $lesson = $lessonRepository->find($id_lesson);

        $certification = $certificationRepository->findOneBy(['user'=>$user, 'lesson'=>$lesson]);
        if(!$certification){
            $certification = new Certification();
            $certification ->setUser($user);
            $certification ->setLesson($lesson);
            $certification ->setObtainedAt(new \DateTimeImmutable);
            $em->persist($certification);
            $em->flush();
        }
        $this->addFlash('success', 'Félicition vous venez de valider une leçon. Vous venez de recevoir une certification!! ');
        return $this-> redirectToRoute('app_cursus', ['id_lesson' => $id_lesson]);
    }
}