<?php

namespace App\Form;

use App\Entity\Asset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetSearchFormType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET)
            ->add('floor', NumberType::class, [
            'required' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('floor')
            ]
        ])->add('title', TextType::class, [
            'required' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('title')
            ]
        ])->add('minSqm', NumberType::class, [
            'required' => false,
            'mapped' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('min-sqm')
            ]
        ])->add('maxSqm', NumberType::class, [
            'required' => false,
            'mapped' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('max-sqm')
            ],
        ])->add('minPrice', MoneyType::class, [
            'required' => false,
            'mapped' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('min-price')
            ]
        ])->add('maxPrice', MoneyType::class, [
            'required' => false,
            'mapped' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('max-price')
            ]
        ])->add('type', ChoiceType::class, [
            'required' => false,
            'placeholder' => false,
            'choices' => [
                'apartment' => $this->translator->trans('apartment'),
                'house' => $this->translator->trans('house'),
                'commercial' => $this->translator->trans('commercial'),
                'industrial' => $this->translator->trans('industrial'),
                'land' => $this->translator->trans('land'),
            ]
        ])->add('term', ChoiceType::class, [
            'required' => false,
            'placeholder' => false,
            'choices' => [
                'rent' => $this->translator->trans('rent'),
                'sale' => $this->translator->trans('sale'),
            ]
        ])->add('address', TextType::class, [
            'required' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('address')
            ]
        ])->add('agencyFee', MoneyType::class, [
            'required' => false,
            'currency' => 'CZK'
        ])->add('userType', ChoiceType::class, [
            'required' => false,
            'mapped' => false,
            'placeholder' => false,
            'choices' => [
                'all' => null,
                'agent' => $this->translator->trans('agent'),
                'owner' => $this->translator->trans('owner'),
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Asset::class,
        ]);
    }
}
