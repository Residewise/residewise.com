<?php

declare(strict_types = 1);

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordFormType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'error_bubbling' => true,
            'invalid_message' => $this->translator->trans('confirm-password-invalid-message'),
            'options' => [
                'trim' => true,
                'attr' => [
                    'class' => 'password-field input-group',
                ],
            ],
            'required' => true,
            'first_options' => [
                'label' => $this->translator->trans('password'),
            ],
            'second_options' => [
                'label' => $this->translator->
                trans('repeat-password'),
            ],
        ]);

        if ($options['data']['isOldPasswordRequired']) {
            $builder->add('oldPassword', PasswordType::class, []);
        }

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([// Configure your form options here
        ]);
    }
}
