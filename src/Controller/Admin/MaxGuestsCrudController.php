<?php

namespace App\Controller\Admin;

use App\Entity\MaxGuests;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MaxGuestsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MaxGuests::class;
    }
}
