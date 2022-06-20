<?php

namespace App\Form;

use App\Entity\Tender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TenderFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('startAt', TextType::class, [
            'mapped' => false,
            'attr' => [
                'class' => 'pl-datepicker'
            ]
        ])->add('endAt', TextType::class, [
            'mapped' => false,
            'attr' => [
                'class' => 'pl-datepicker-second-input'
            ]
        ])->add('minimumBid', MoneyType::class, [
            'required' => false,
            'mapped' => false,
            'currency' => 'CZK',
            'attr' => [
                'value' => $options['asset']->getFee()
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('asset');
        $resolver->setDefaults([
            'data_class' => Tender::class,
        ]);
    }
}
