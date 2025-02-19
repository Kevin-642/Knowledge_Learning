<?php

namespace App\Controller;

use App\Repository\CertificationRepository;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CertificationController extends AbstractController
{
    #[Route('/certifications', name: 'app_certifications')]
    public function index(CertificationRepository $certificationRepository, ThemeRepository $themeRepository): Response
    {
        //recupération de l'utilisateur
        $user = $this->getUser();
        //récupération des certification obtenue
        $certifications = $certificationRepository->findBy(['user'=>$user]);
        //Récupération de tous les themes ,cursus et leçon
        $themes = $themeRepository->findAll();

        $data=[];
        foreach($themes as $theme){
            $cursusList=[];
            foreach($theme->getCursuses() as $cursus){
                $lessonsList=[];
                foreach($cursus->getLesson() as $lesson){
                    foreach ($certifications as $certification){
                        if($certification->getLesson() === $lesson){
                            $lessonsList[]=[
                                'lesson'=>$lesson,
                                'obtainedAt'=>$certification->getObtainedAt(),
                            ];
                        }
                    }
                }
                if(!empty($lessonsList)){
                    $cursusList[]= [
                        'cursus'=>$cursus,
                        'lessonsList'=>$lessonsList
                    ];
                }
            }
            if (!empty($cursusList)) {
                $data[] = [
                    'theme' => $theme,
                    'cursusList' => $cursusList,
                ];
            }
        }


        return $this->render('certification/index.html.twig', [
            'certifications'=>$data
        ]);
    }
}
