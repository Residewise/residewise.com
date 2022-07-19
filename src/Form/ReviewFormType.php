<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Asset;
use App\Entity\Review;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ReviewFormType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Review $data */
        $data = $options['data'];
        $builder->add('rating', RangeType::class, [
            'attr' => [
                'step' => 0.5,
                'min' => 0,
                'max' => 5,
                'value' => 2.5,
                'oninput' => 'review_form_amount.value=review_form_rating.value',
            ],
        ])->add('amount', NumberType::class, [
            'mapped' => false,
            'required' => false,
            'attr' => [
                'oninput' => 'review_form_rating.value=review_form_amount.value',
            ],
        ])->add('notes', TextareaType::class)
            ->add('asset', EntityType::class, [
                'placeholder' => $this->translator->trans('review-property-lived-in-placeholder'),
                'required' => false,
                'class' => Asset::class,
                'empty_data' => null,
                'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('a')
                    ->andWhere('a.owner = :user')
                    ->setParameter('user', $data->getUser()),
                'choice_label' => 'title',
            ])->add('acknowledgement', CheckboxType::class, [
                'required' => true,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
