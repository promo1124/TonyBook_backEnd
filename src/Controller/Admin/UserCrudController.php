<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname'),
            TextEditorField::new('lastname'),
            TextEditorField::new('email'),
            TextEditorField::new('address')->hideOnIndex(),
            NumberField::new('cp')->hideOnIndex(),
            TextEditorField::new('town')->hideOnIndex(),
            TextEditorField::new('country')->hideOnIndex(),
            TextEditorField::new('phoneNumber'),
        ];
    }

}
