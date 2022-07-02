<?php

namespace App\Controller\Admin;

use App\Entity\Amenity;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AmenityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Amenity::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('icon')
        ];
    }

}
