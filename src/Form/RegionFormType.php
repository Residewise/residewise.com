<?php

namespace App\Form;

use App\Service\RegionalSettingsService\RegionalSettingsService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegionFormType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly RegionalSettingsService $regionalSettingsService,
        private readonly LocaleSwitcher $localeSwitcher
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod('get')
            ->add('language', LanguageType::class, [
                'label' => $this->translator->trans('language'),
                'choice_loader' => null,
                'mapped' => false,
                'choices' => [
                    'English' => 'en',
                    'Češka' => 'cs',
                ],
                'data' => $this->regionalSettingsService->getLocale(),
            ])
            ->add('region', CountryType::class, [
                'label' => $this->translator->trans('region-country'),
                'mapped' => false,
                'attr' => [
                    'data-timezone-target' => 'region',
                ],
                'preferred_choices' => [
                    'en',
                    'cs',
                ],
                'data' => $this->regionalSettingsService->getRegion()
            ])
            ->add('currency', CurrencyType::class, [
                'label' => $this->translator->trans('currency'),
                'mapped' => false,
                'data' => $this->regionalSettingsService->getCurrency(),
            ])
            ->add('timezone', HiddenType::class, [
                'attr' => [
                    'data-timezone-target' => 'timezone',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'request',
        ]);
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
