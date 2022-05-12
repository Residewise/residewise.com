<?php

namespace App\Form;

use App\Entity\Asset;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAttribute('data-action', 'validation#validateAll')
            ->add('address', TextType::class)
            ->add('floor', NumberType::class, [
                'label' => $this->translator->trans('floor')
            ])
            ->add('images', FileType::class, [
                'label' => $this->translator->trans('images'),
                'multiple' => true,
                'mapped' => false,
                'help' => $this->translator->trans('image-upload-help'),
                'attr' => [
                    'data-action' => 'input->validation#validate',
                    'data-target' => 'validation.field',
                ]
            ])
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('title'),
            ])
            ->add('description', TextareaType::class, [
                'label' => $this->translator->trans('description'),
                'attr' => [
                    'data-controller' =>'textarea-autogrow',
                    'data-character-counter-target' => 'input',
                    'style' => 'resize:none'
                ]
            ])
            ->add('sqm', NumberType::class, [
                'label' => $this->translator->trans('sqm')
            ])
            ->add('fee', MoneyType::class, [
                'currency' => 'CZK',
                'label' => $this->translator->trans('fee')
            ])
            ->add('type', ChoiceType::class, [
                'label' => $this->translator->trans('type'),
                'choices' => [
                    $this->translator->trans('apartment') => 'apartment',
                    $this->translator->trans('house') => 'house',
                    $this->translator->trans('land') => 'land',
                    $this->translator->trans('commercial') => 'commercial',
                    $this->translator->trans('industrial') => 'industrial',
                    $this->translator->trans('other') => 'other',
                ]
            ])
            ->add('term', ChoiceType::class, [
                'label' => $this->translator->trans('terms'),
                'choices' => [
                    $this->translator->trans('rent') => 'rent',
                    $this->translator->trans('sale') => 'sale',
                ]
            ])
            ->add('latitude', HiddenType::class, [
                'attr' => [
                    'data-asset-location-target' => 'latitude'
                ]
            ])
            ->add('longitude', HiddenType::class, [
                'attr' => [
                    'data-asset-location-target' => 'longitude'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Asset::class,
        ]);
    }
}
