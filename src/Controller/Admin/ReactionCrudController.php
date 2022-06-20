<?php

namespace App\Controller\Admin;

use App\Entity\Reaction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

class ReactionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reaction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('owner'),
            AssociationField::new('asset'),
            ChoiceField::new('type')->setChoices([
                'like' => 'like',
                'dislike' => 'dislike'
            ]),
        ];
    }

}
