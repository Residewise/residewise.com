<?php

namespace App\Form;

use App\Entity\Bid;
use App\Service\RegionalSettingsService\RegionalSettingsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BidFormType extends AbstractType
{
    public function __construct(
        private readonly RegionalSettingsService $regionalSettingsService
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', MoneyType::class, [
                'currency' => $this->regionalSettingsService->getCurrency(),
                'data' => $options['suggested_bid_amount']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'suggested_bid_amount'
        ]);
        $resolver->setDefaults([
            'data_class' => Bid::class,
        ]);
    }
}
