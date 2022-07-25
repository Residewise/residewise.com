<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Tender;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class TenderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tender::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('asset'),
            DateField::new('startAt'),
            DateField::new('endAt'),
            DateField::new('createdAt'),
        ];
    }
}
