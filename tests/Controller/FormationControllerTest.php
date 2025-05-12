<?php

namespace App\Tests\Controller;

use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormationControllerTest extends WebTestCase
{
    // Test pour vérifier les relations entre les entités Theme, Cursus et Lesson
    public function testFormationRelation()
    {
        // Création d'un objet Theme
        $theme = new Theme;
        // Définition du nom du thème
        $theme->setNameTheme('Test Theme');

        // Vérification que le nom du thème a bien été défini
        $this->assertSame('Test Theme', $theme->getNameTheme());

        // Création d'un objet Cursus
        $cursus = new Cursus;
        // Définition des attributs du cursus
        $cursus->setNameCursus('Test Cursus');
        $cursus->setPrice(35);
        $cursus->setDescription('Description du Test Cursus');
        $cursus->setTheme($theme);  // Lien avec le thème créé précédemment

        // Vérification des attributs du cursus
        $this->assertSame('Test Cursus', $cursus->getNameCursus());
        $this->assertEquals(35, $cursus->getPrice());
        $this->assertSame('Description du Test Cursus', $cursus->getDescription());
        $this->assertSame($theme, $cursus->getTheme());  // Vérification que le thème est bien lié au cursus

        // Création d'un objet Lesson
        $lesson = new Lesson;
        // Définition des attributs de la leçon
        $lesson->setNameLesson('Test Leçon');
        $lesson->setPrice(17);
        $lesson->setContent('Contenu de la leçon');
        $lesson->setDescription('Description de la leçon');
        $lesson->setCursus($cursus);  // Lien avec le cursus créé précédemment

        // Vérification des attributs de la leçon
        $this->assertSame('Test Leçon', $lesson->getNameLesson());
        $this->assertEquals(17, $lesson->getPrice());
        $this->assertSame('Contenu de la leçon', $lesson->getContent());
        $this->assertSame('Description de la leçon', $lesson->getDescription());
        $this->assertSame($cursus, $lesson->getCursus());  // Vérification que le cursus est bien lié à la leçon
    }
}