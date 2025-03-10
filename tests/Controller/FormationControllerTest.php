<?php

namespace App\Tests\Controller;

use App\Entity\Cursus;
use App\Entity\Lesson;
use App\Entity\Theme;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormationControllerTest extends WebTestCase
{
    public function testFormationRelation()
    {
        $theme = new Theme ;
        $theme -> setNameTheme('Test Theme');

        $this->assertSame('Test Theme', $theme->getNameTheme());

        $cursus = new Cursus;
        $cursus -> setNameCursus('Test Cursus');
        $cursus -> setPrice(35);
        $cursus -> setDescription('Description du Test Cursus');
        $cursus -> setTheme($theme);

        $this->assertSame('Test Cursus', $cursus->getNameCursus());
        $this->assertEquals(35, $cursus->getPrice());
        $this->assertSame('Description du Test Cursus', $cursus->getDescription());
        $this->assertSame($theme, $cursus->getTheme());

        $lesson = new Lesson;
        $lesson -> setNameLesson('Test Leçon');
        $lesson -> setPrice(17);
        $lesson -> setContent('Contenu de la leçon');
        $lesson -> setDescription('Description de la leçon');
        $lesson -> setCursus($cursus);

        $this->assertSame('Test Leçon', $lesson->getNameLesson());
        $this->assertEquals(17, $lesson->getPrice());
        $this->assertSame('Contenu de la leçon', $lesson->getContent());
        $this->assertSame('Description de la leçon', $lesson->getDescription());
        $this->assertSame($cursus, $lesson->getCursus());

    }
}