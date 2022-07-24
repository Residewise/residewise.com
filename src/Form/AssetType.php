<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Amenity;
use App\Entity\Asset;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;

class AssetType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $s = null;
        $builder->add('address', TextType::class)->add('floor', NumberType::class, [
            'label' => $this->translator->trans('floor'),
        ])->add('images', FileType::class, [
            'mapped' => false,
            'label' => false,
            'attr' => [
                'placeholder' => $this->translator->trans('dropzone.placeholder'),
            ],
            'multiple' => true,
            'help' => $this->translator->trans('image-upload-help'),
        ])->add('title', TextType::class, [
            'label' => $this->translator->trans('title'),
        ])->add('description', TextareaType::class, [
            'label' => $this->translator->trans('description'),
            'attr' => [
                'data-controller' => 'textarea-autogrow',
                'data-character-counter-target' => 'input',
                'style' => 'resize:none',
            ],
        ])->add('sqm', NumberType::class, [
            'label' => $this->translator->trans('sqm'),
        ])->add('price', MoneyType::class, [
            'help' => $this->translator->trans('fee-input-help'),
            'currency' => 'CZK',
            'label' => $this->translator->trans('price'),
        ])->add('type', ChoiceType::class, [
            'label' => $this->translator->trans('type'),
            'choices' => [
                $this->translator->trans('apartment') => 'apartment',
                $this->translator->trans('house') => 'house',
                $this->translator->trans('land') => 'land',
                $this->translator->trans('commercial') => 'commercial',
                $this->translator->trans('industrial') => 'industrial',
                $this->translator->trans('other') => 'other',
            ],
        ])->add('term', ChoiceType::class, [
            'label' => $this->translator->trans('terms'),
            'choices' => [
                $this->translator->trans('rent') => 'rent',
                $this->translator->trans('sale') => 'sale',
            ],
        ])->add('latitude', HiddenType::class, [
            'attr' => [
                'data-asset-location-target' => 'latitude',
            ],
        ])->add('longitude', HiddenType::class, [
            'attr' => [
                'data-asset-location-target' => 'longitude',
            ],
        ])->add('amenities', EntityType::class, [
            'choice_translation_domain' => true,
            'class' => Amenity::class,
            'choice_label' => 'name',
            'tom_select_options' => [
                'create' => true,
            ],
            'multiple' => true,
            'autocomplete' => true,
        ]);

        if ($s) {
            $builder->add('agencyFee', MoneyType::class, [
                'currency' => 'CZK',
                'label' => $this->translator->trans('agency-fee'),
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Asset::class,
        ]);
    }
}
