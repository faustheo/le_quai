<?php

namespace App\Controller\Admin;

use App\Entity\RestaurantCard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RestaurantCardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RestaurantCard::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
