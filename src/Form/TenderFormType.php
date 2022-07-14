<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Tender;
use App\Service\RegionalSettingsService\RegionalSettingsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TenderFormType extends AbstractType
{

    public function __construct(
        private readonly RegionalSettingsService $regionalSettingsService,
        private readonly TranslatorInterface     $translator,

    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('range', TextType::class, [
            'mapped' => false,
            'attr' => [
                'data-date-range-picker-target' => 'picker',
            ],
        ])
            ->add('minimumBid', MoneyType::class, [
                'required' => false,
                'mapped' => false,
                'currency' => $this->regionalSettingsService->getCurrency(),
                'attr' => [
                    'value' => $options['asset']->getPrice(),
                ],
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
