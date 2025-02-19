<?php

namespace App\Controller\Admin;

use App\Entity\Theme;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class ThemeCrudController extends AbstractCrudController
{
    use  Trait\ReadOnlyTrait;
    
    public static function getEntityFqcn(): string
    {
        return Theme::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id_theme')
                ->onlyOnIndex(),
            TextField::new('name_theme'),
        ];
    }

}
