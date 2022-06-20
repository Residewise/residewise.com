<?php

namespace App\Controller\Admin;

use App\Entity\Publication;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class PublicationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Publication::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('asset'),
            BooleanField::new('isApproved'),
            DateTimeField::new('startsAt'),
            DateTimeField::new('endsAt'),
        ];
    }
}
