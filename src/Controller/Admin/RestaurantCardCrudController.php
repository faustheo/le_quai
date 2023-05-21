<?php

namespace App\Controller\Admin;

use App\Entity\RestaurantCard;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class RestaurantCardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RestaurantCard::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title')->setFormTypeOption('attr', [
                'placeholder' => 'Formule Diner',
            ]),
            TextField::new('subtitle')->setFormTypeOption('attr', [
                'placeholder' => '(du lundi au vendredi soir)',
            ]),
            TextareaField::new('description')->setFormTypeOption('attr', [
                'placeholder' => 'entree + plat + dessert',
            ]),
            MoneyField::new('price')->setCurrency('EUR'),
        ];
    }
}
