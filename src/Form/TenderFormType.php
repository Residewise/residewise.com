<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Asset;
use App\Entity\Tender;
use App\Service\RegionalSettingsService\RegionalSettingsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TenderFormType extends AbstractType
{
    public function __construct(
        private readonly RegionalSettingsService $regionalSettingsService,
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Asset $asset */
        $asset = $options['asset'];
        $builder->add('startAt', DateType::class, [
            'widget' => 'single_text',
            'input' => 'datetime_immutable'
        ])
        ->add('endAt', DateType::class, [
            'widget' => 'single_text',
            'input' => 'datetime_immutable'
        ])
            ->add('minimumBid', MoneyType::class, [
                'required' => false,
                'mapped' => false,
                'currency' => $this->regionalSettingsService->getCurrency(),
                'attr' => [
                    'value' => $asset->getPrice(),
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
