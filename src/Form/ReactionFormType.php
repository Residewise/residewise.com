<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReactionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('like', SubmitType::class, [
            'label' => false,
            'attr' => [
                'class' => 'btn mdi mdi-heart-outline'
            ]
        ])->add('dislike', SubmitType::class, [
            'label' => false,
            'attr' => [
                'class' => 'btn mdi mdi-thumb-down-outline'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }
}
