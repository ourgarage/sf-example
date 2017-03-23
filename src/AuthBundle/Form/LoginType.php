<?php

namespace AuthBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', EmailType::class, [
                'label' => 'login_username',
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'login_password',
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function getBlockPrefix()
    {
        return null;
    }
}
