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
                -> setImages(images: 'CursusGuitare.png')
                -> setDescription('Ce cursus d\'initiation à la guitare permet aux débutants de découvrir les bases de l\'instrument, tout en apprenant à jouer des morceaux simples et à maîtriser les techniques essentielles.')
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable());
        $manager-> persist($guitare);

        $piano=new Cursus();
        $piano-> setNameCursus("Cursus d'initiation au piano")
                -> setPrice(50)
                -> setTheme($themeMusique)
                -> setImages('CursusPiano.png')
                -> setDescription('Ce cursus d\'initiation au piano offre une approche douce et progressive pour apprendre les fondamentaux de l\'instrument, avec des exercices adaptés aux débutants.')
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable());
        $manager-> persist($piano);

        //Cursus Informatique
        $web=new Cursus();
        $web-> setNameCursus("Cursus d'initiation au developpement web")
                -> setPrice(60)
                -> setTheme($themeInformatique)
                -> setImages(images: 'CursusDev.png')
                -> setDescription('Ce cursus d\'initiation au développement web offre une introduction complète aux langages essentiels comme HTML, CSS et JavaScript, permettant aux débutants de créer leurs premières pages web')
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable());
        $manager-> persist($web);


        //Cursus Jardinage
        $jardinage=new Cursus();
        $jardinage-> setNameCursus("Cursus d'initiation au jardinage")
                -> setPrice(30)
                -> setTheme($themeJardinage)
                -> setImages('CursusJardinage.png')
                -> setDescription('Ce cursus d\'initiation au jardinage permet aux débutants d`apprendre les techniques fondamentales pour cultiver leur jardin, de la préparation du sol à l`entretien des plantes.')
                ->setCreatedAt(new DateTimeImmutable())
                ->setUpdatedAt(new DateTimeImmutable());
        $manager-> persist($jardinage);

        //Cursus Cuisine
        $cuisine=new Cursus();
        $cuisine-> setNameCursus("Cursus d'initiation à la cuisine")
                -> setPrice(44)
                -> setTheme($themeCuisine)
                -> setImages('CursusCuisine.png')
                -> setDescription('À travers des ateliers pratiques, ce programme offre une approche ludique et conviviale pour découvrir les secrets de la cuisine maison et développer sa créativité en cuisine.');
        $manager-> persist($cuisine);
        
        //Cursus Art de la table
        $dressage=new Cursus();
        $dressage-> setNameCursus("Cursus d'initiation à la cuisine")
                -> setPrice(48)
                -> setTheme($themeCuisine)
                -> setImages('CursusArtdelaTable.png')
                -> setDescription('Ce cursus d\'initiation aux arts de la table permet d\'apprendre les règles de l`élégance et du raffinement, de la mise en place à l\'art de servir avec style.');
        $manager-> persist($dressage);

        //Lesson
        //Musique-guitare
        $lesson1guitare = new Lesson;
        $lesson1guitare -> setNameLesson('Leçon 1: Découverte de l\'instrument')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Voyage au cœur des cordes.mp4')
                        -> setCertificationImage('CoursGuitare1.png')
                        -> setCursus($guitare)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours de guitare est conçu pour les débutants souhaitant apprendre les bases de l\'instrument. Il couvre les techniques essentielles, telles que les accords, le strumming, et la lecture de partitions, tout en permettant aux élèves de jouer leurs premières chansons. Grâce à une approche progressive et personnalisée, chaque étudiant pourra développer ses compétences à son propre rythme et explorer son potentiel musical.');
        $manager->persist($lesson1guitare);

        $lesson2guitare = new Lesson;
        $lesson2guitare -> setNameLesson('Leçon 2: Les accords et les gammes')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Guitare.mp4')
                        -> setCertificationImage('CoursGuitare2.png')
                        -> setCursus($guitare)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours de guitare est conçu pour les débutants souhaitant apprendre les bases de l\'instrument. Il couvre les techniques essentielles, telles que les accords, le strumming, et la lecture de partitions, tout en permettant aux élèves de jouer leurs premières chansons. Grâce à une approche progressive et personnalisée, chaque étudiant pourra développer ses compétences à son propre rythme et explorer son potentiel musical.');
        $manager->persist($lesson2guitare);
        //Musique-piano
        $lesson1piano = new Lesson;
        $lesson1piano -> setNameLesson('Leçon 1: Découverte de l\'instrument')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Piano.mp4')
                        -> setCertificationImage('CoursPiano1.png')
                        -> setCursus($piano)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours de piano est idéal pour les débutants qui souhaitent découvrir l\'instrument et ses fondamentaux. Les élèves apprendront à lire des partitions, à maîtriser les techniques de base, ainsi qu\'à jouer des morceaux simples. L\'approche progressive permet de développer à la fois la technique et l\'oreille musicale, tout en offrant une expérience enrichissante et ludique pour chaque apprenant, quel que soit son rythme.');
        $manager->persist($lesson1piano);

        $lesson2piano = new Lesson;
        $lesson2piano -> setNameLesson('Leçon 2: Les accords et les gammes')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Maîtrisez accords et gammes au piano.mp4')
                        -> setCertificationImage('CoursPiano2.png')
                        -> setCursus($piano)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours de piano est idéal pour les débutants qui souhaitent découvrir l\'instrument et ses fondamentaux. Les élèves apprendront à lire des partitions, à maîtriser les techniques de base, ainsi qu\'à jouer des morceaux simples. L\'approche progressive permet de développer à la fois la technique et l\'oreille musicale, tout en offrant une expérience enrichissante et ludique pour chaque apprenant, quel que soit son rythme.');
        $manager->persist($lesson2piano);

        //Informatique-web
        $lesson1web = new Lesson;
        $lesson1web -> setNameLesson('Leçon 1: Les langages HTML et CSS')
                        -> setPrice(32)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('CoursHTMLCSS.mp4')
                        -> setCertificationImage('CoursDev1.png')
                        -> setCursus($web)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours de développement web est destiné aux débutants souhaitant acquérir les compétences de base pour créer des sites web interactifs. Les étudiants apprendront les langages fondamentaux tels que HTML, CSS et JavaScript, tout en découvrant les principes de conception et de structure d\'une page web. Grâce à des exercices pratiques et des projets concrets, ce cours permet de développer des compétences solides et de se lancer dans la création de sites modernes et fonctionnels.');
        $manager->persist($lesson1web);

        $lesson2web = new Lesson;
        $lesson2web -> setNameLesson('Leçon 2: Dynamiser votre site avec JavaScript')
                        -> setPrice(32)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Dynamiser_votre_site_avec_JavaScript.mp4')
                        -> setCertificationImage('CoursDev2.png')
                        -> setCursus($web)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours de développement web est destiné aux débutants souhaitant acquérir les compétences de base pour créer des sites web interactifs. Les étudiants apprendront les langages fondamentaux tels que HTML, CSS et JavaScript, tout en découvrant les principes de conception et de structure d\'une page web. Grâce à des exercices pratiques et des projets concrets, ce cours permet de développer des compétences solides et de se lancer dans la création de sites modernes et fonctionnels.');
        $manager->persist($lesson2web);

        //Jardinage-jardinage
        $lesson1jardinage = new Lesson;
        $lesson1jardinage -> setNameLesson('Leçon 1: Les outils du jardinier')
                        -> setPrice(16)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Outils_jardinier.mp4')
                        -> setCertificationImage('CoursJardinage1.png')
                        -> setCursus($jardinage)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours d\'initiation au jardinage est conçu pour les débutants désireux d\'apprendre les techniques de base pour cultiver et entretenir un jardin. Les participants découvriront les différentes étapes de la plantation, de l’entretien des plantes, ainsi que des conseils pratiques sur le choix des plantes en fonction des saisons et des sols. Grâce à une approche pratique et accessible, ce cours offre toutes les clés pour réussir son jardin et profiter de la beauté de la nature au quotidien.');
        $manager->persist($lesson1jardinage);

        $lesson2jardinage = new Lesson;
        $lesson2jardinage -> setNameLesson('Leçon 2: Jardiner avec la lune')
                        -> setPrice(16)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Jardinage.mp4')
                        -> setCertificationImage('CoursJardinage2.png')
                        -> setCursus($jardinage)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours d\'initiation au jardinage est conçu pour les débutants désireux d\'apprendre les techniques de base pour cultiver et entretenir un jardin. Les participants découvriront les différentes étapes de la plantation, de l’entretien des plantes, ainsi que des conseils pratiques sur le choix des plantes en fonction des saisons et des sols. Grâce à une approche pratique et accessible, ce cours offre toutes les clés pour réussir son jardin et profiter de la beauté de la nature au quotidien.');
        $manager->persist($lesson2jardinage);

        //Cuisine-cuisine
        $lesson1cuisine = new Lesson;
        $lesson1cuisine -> setNameLesson('Leçon 1: Les modes de cuisson ')
                        -> setPrice(23)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Mode_cuisson.mp4')
                        -> setCertificationImage('CoursCuisine1.png')
                        -> setCursus($cuisine)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours de cuisine est destiné à ceux qui souhaitent apprendre à préparer des plats délicieux et équilibrés, même sans expérience préalable. Les participants découvriront les techniques de base, comme la préparation des ingrédients, la cuisson et la présentation des plats. À travers des recettes simples et savoureuses, ce cours offre une approche ludique et pratique pour maîtriser les fondamentaux de la cuisine maison et éveiller sa créativité culinaire.');
        $manager->persist($lesson1cuisine);

        $lesson2cuisine = new Lesson;
        $lesson2cuisine -> setNameLesson('Leçon 2: Les saveurs')
                        -> setPrice(23)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Saveurs du Monde.mp4')
                        -> setCertificationImage('CoursCuisine2.png')
                        -> setCursus($cuisine)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours de cuisine est destiné à ceux qui souhaitent apprendre à préparer des plats délicieux et équilibrés, même sans expérience préalable. Les participants découvriront les techniques de base, comme la préparation des ingrédients, la cuisson et la présentation des plats. À travers des recettes simples et savoureuses, ce cours offre une approche ludique et pratique pour maîtriser les fondamentaux de la cuisine maison et éveiller sa créativité culinaire.');
        $manager->persist($lesson2cuisine);

        //Cuisine-dressage
        $lesson1dressage = new Lesson;
        $lesson1dressage -> setNameLesson('Leçon 1: Mettre en œuvre le style dans l\'assiette ')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Transformez_Votre Assiette_en_Œuvre_dArt.mp4')
                        -> setCertificationImage('CoursArtTable1.png')
                        -> setCursus($dressage)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours aux arts de la table offre une initiation complète aux techniques de mise en scène d’un repas élégant et raffiné. Les participants apprendront les règles de la décoration de table, la sélection des accessoires et l’organisation d’un service soigné, tout en découvrant l\'art de créer une ambiance harmonieuse pour recevoir. Grâce à des conseils pratiques et créatifs, ce cours permet de maîtriser l\'art de recevoir et de transformer chaque repas en un moment inoubliable.');
        $manager->persist($lesson1dressage);

        $lesson2dressage = new Lesson;
        $lesson2dressage -> setNameLesson('Leçon 2: Harmoniser un repas à quatre plats')
                        -> setPrice(26)
                        -> setContent('Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quis mollitia velit quod rem porro, enim sapiente nobis sed amet maxime. Soluta assumenda quis earum, veniam fugit nobis sint officia?')
                        -> setVideoUrl('Harmonisez_Votre_Repas_en_Quatre_Étapes!.mp4')
                        -> setCertificationImage('CoursArtTable2.png')
                        -> setCursus($dressage)
                        -> setCreatedAt(new DateTimeImmutable)
                        -> setUpdatedAt(new DateTimeImmutable)
                        -> setDescription('Ce cours aux arts de la table offre une initiation complète aux techniques de mise en scène d’un repas élégant et raffiné. Les participants apprendront les règles de la décoration de table, la sélection des accessoires et l’organisation d’un service soigné, tout en découvrant l\'art de créer une ambiance harmonieuse pour recevoir. Grâce à des conseils pratiques et créatifs, ce cours permet de maîtriser l\'art de recevoir et de transformer chaque repas en un moment inoubliable.');
        $manager->persist($lesson2dressage);


        $manager->flush();
     }
}