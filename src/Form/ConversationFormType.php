<?php

namespace App\Form;

use App\Entity\Conversation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConversationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('autocomplete', TextType::class, [
                'required' => false,
                'mapped' => false,
                'attr'=> [
                    'data-autocomplete-select-target' => 'textInput',
                ]
            ])
            ->add('users', HiddenType::class, [
                'attr'=>[
                    'data-autocomplete-select-target' => 'input'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conversation::class,
        ]);
    }
}
