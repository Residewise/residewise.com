<?php

namespace App\Form;

use App\Entity\Asset;
use App\Entity\Review;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Review $data */
        $data = $options['data'];
        $builder->add('rating', NumberType::class, [
            'attr' => [
                'data-controller' => 'rating',
                'data-rating-target' => 'input',
            ]
        ])->add('notes', TextareaType::class)->add('asset', EntityType::class, [
            'class' => Asset::class,
            'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('a')
                ->andWhere('a.owner = :user')
                ->setParameter('user', $data->getReviewee())
                ->andWhere('a.isPublic = :true')->setParameter('true', true),
            'choice_label' => 'title'
        ])
            ->add('acknowledgement', CheckboxType::class, [
                'required' => true,
                'mapped' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
