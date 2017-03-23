<?php

namespace UserBundle\Form;

use UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nickname', TextType::class, ['label' => 'nick'])
            ->add('email', EmailType::class, ['label' => 'email'])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'password'],
                'second_options' => ['label' => 'repeat_password'],
                'required' => $options['password_required'],
                'constraints' => function ($options) {
                    if ($options['password_required']) {
                        return [new Assert\NotBlank()];
                    }

                    return [];
                },
            ])
            ->add('role', ChoiceType::class, [
                'choices' => array_flip(User::ROLES_LIST),
                'expanded' => true,
                'multiple' => false,
                'label' => 'role',
            ])
            ->add('status', ChoiceType::class, [
                'choices' => array_flip(User::STATUSES_LIST),
                'expanded' => false,
                'multiple' => false,
                'label' => 'status',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'password_required' => false,
        ]);
    }
}
