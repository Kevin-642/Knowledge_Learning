<?php

namespace App\DataFixtures;

use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Entity\Theme;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CursusFixtures extends Fixture
{
     public function load(ObjectManager $manager):void
     {
        //Theme
        $themeMusique = new Theme();
        $themeMusique -> setNameTheme('Musique');
        $manager-> persist($themeMusique);

        $themeInformatique = new Theme();
        $themeInformatique -> setNameTheme('Informatique');
        $manager-> persist($themeInformatique);

        $themeJardinage = new Theme();
        $themeJardinage -> setNameTheme('Jardinage');
        $manager-> persist($themeJardinage);

        $themeCuisine = new Theme();
        $themeCuisine -> setNameTheme('Cuisine');
        $manager-> persist($themeCuisine);

        //Cursus
        //Cursus Musique
        $guitare=new Cursus();
        $guitare-> setNameCursus("Cursus d'initiation à la guitare")
                -> setPrice(50)
                -> setTheme($themeMusique)
                -> setImages('1a5ac707167ca8ef325246145a2271dfe06567da.jpg')
                -> setDescription('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
        $manager-> persist($guitare);

        $piano=new Cursus();
        $piano-> setNameCursus("Cursus d'initiation au piano")
                -> setPrice(50)
                -> setTheme($themeMusique)
                -> setImages('1a5ac707167ca8ef325246145a2271dfe06567da.jpg')
                -> setDescription('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
        $manager-> persist($piano);

        //Cursus Informatique
        $web=new Cursus();
        $web-> setNameCursus("Cursus d'initiation au developpement web")
                -> setPrice(60)
                -> setTheme($themeInformatique)
                -> setImages('1a5ac707167ca8ef325246145a2271dfe06567da.jpg')
                -> setDescription('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
        $manager-> persist($web);


        //Cursus Jardinage
        $jardinage=new Cursus();
        $jardinage-> setNameCursus("Cursus d'initiation au jardinage")
                -> setPrice(30)
                -> setTheme($themeJardinage)
                -> setImages('1a5ac707167ca8ef325246145a2271dfe06567da.jpg')
                -> setDescription('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable());
        $manager-> persist($jardinage);

        //Cursus Cuisine
        $cuisine=new Cursus();
        $cuisine-> setNameCursus("Cursus d'initiation à la cuisine")
                -> setPrice(44)
                -> setTheme($themeCuisine)
                -> setImages('1a5ac707167ca8ef325246145a2271dfe06567da.jpg')
                -> setDescription('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?');
        $manager-> persist($cuisine);

        $dressage=new Cursus();
        $dressage-> setNameCursus("Cursus d'initiation à la cuisine")
                -> setPrice(48)
                -> setTheme($themeCuisine)
                -> setImages('1a5ac707167ca8ef325246145a2271dfe06567da.jpg')
                -> setDescription('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?');
        $manager-> persist($dressage);

        //Lesson
        //Musique-guitare
        $lesson1guitare = new Lesson;
        $lesson1guitare -> setNameLesson('Leçon 1: Découverte de l\'instrument')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('guitare.png')
                        -> setCursus($guitare)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson1guitare);

        $lesson2guitare = new Lesson;
        $lesson2guitare -> setNameLesson('Leçon 2: Les accords et les gammes')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('guitare.png')
                        -> setCursus($guitare)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson2guitare);
        //Musique-piano
        $lesson1piano = new Lesson;
        $lesson1piano -> setNameLesson('Leçon 1: Découverte de l\'instrument')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('piano.png')
                        -> setCursus($piano)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson1piano);

        $lesson2piano = new Lesson;
        $lesson2piano -> setNameLesson('Leçon 2: Les accords et les gammes')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('piano.png')
                        -> setCursus($piano)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson2piano);

        //Informatique-web
        $lesson1web = new Lesson;
        $lesson1web -> setNameLesson('Leçon 1: Les langages HTML et CSS')
                        -> setPrice(32)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('info1.png')
                        -> setCursus($web)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson1web);

        $lesson2web = new Lesson;
        $lesson2web -> setNameLesson('Leçon 2: Dynamiser votre site avec JavaScript')
                        -> setPrice(32)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('info2.png')
                        -> setCursus($web)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson2web);

        //Jardinage-jardinage
        $lesson1jardinage = new Lesson;
        $lesson1jardinage -> setNameLesson('Leçon 1: Les outils du jardinier')
                        -> setPrice(16)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('jardinage1.png')
                        -> setCursus($jardinage)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson1jardinage);

        $lesson2jardinage = new Lesson;
        $lesson2jardinage -> setNameLesson('Leçon 2: Jardiner avec la lune')
                        -> setPrice(16)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('jardinage2.png')
                        -> setCursus($jardinage)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson2jardinage);

        //Cuisine-cuisine
        $lesson1cuisine = new Lesson;
        $lesson1cuisine -> setNameLesson('Leçon 1: Les modes de cuisson ')
                        -> setPrice(23)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('cuisine1.png')
                        -> setCursus($cuisine)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson1cuisine);

        $lesson2cuisine = new Lesson;
        $lesson2cuisine -> setNameLesson('Leçon 2: Les saveurs')
                        -> setPrice(23)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('cuisine1.png')
                        -> setCursus($cuisine)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson2cuisine);

        //Cuisine-dressage
        $lesson1dressage = new Lesson;
        $lesson1dressage -> setNameLesson('Leçon 1: Mettre en œuvre le style dans l’assiette ')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('cuisine2.png')
                        -> setCursus($dressage)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson1dressage);

        $lesson2dressage = new Lesson;
        $lesson2dressage -> setNameLesson('Leçon 2: Harmoniser un repas à quatre plats')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('5fe7fbf29fd2f7cb87f75be5d6ed4fa3d4f8395c.mp4')
                        -> setCertificationImage('cuisine2.png')
                        -> setCursus($dressage)
                        -> setCreatedAt(new \DateTimeImmutable)
                        -> setUpdatedAt(new \DateTimeImmutable)
                        -> setDescription('Introductuion bla-bla-bla');
        $manager->persist($lesson2dressage);


        $manager->flush();
     }
}