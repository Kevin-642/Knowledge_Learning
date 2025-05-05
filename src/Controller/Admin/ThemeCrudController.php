<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use App\Controller\Admin\Trait\ReadOnlyTrait; // ✅ Import correct
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ThemeCrudController extends AbstractCrudController
{
    use ReadOnlyTrait;

    public static function getEntityFqcn(): string
    {
        return Theme::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id_theme')->onlyOnIndex(),
            TextField::new('name_theme'),
        ];
    }
}