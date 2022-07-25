<?php

declare(strict_types = 1);

namespace App\Controller\Admin;

use App\Entity\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AssetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Asset::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextareaField::new('description')->onlyOnForms(),
            NumberField::new('sqm'),
            NumberField::new('longitude'),
            NumberField::new('latitude'),
            ChoiceField::new('type')->setChoices([
                'apartment' => 'apartment',
                'house' => 'house',
                'commercial' => 'commercial',
                'industrial' => 'industrial',
                'other' => 'other',
            ]),
            ChoiceField::new('term')->setChoices([
                'rent' => 'rent',
                'sale' => 'sale',
            ]),
            MoneyField::new('price')->setCurrency('CZK'),
            AssociationField::new('owner'),
            AssociationField::new('reviews'),
            AssociationField::new('reactions'),
            AssociationField::new('images')->onlyOnIndex(),
            AssociationField::new('reactions'),
            TextareaField::new('address'),
            NumberField::new('floor'),
            AssociationField::new('amenities'),
            DateTimeField::new('createdAt'),
        ];
    }
}
