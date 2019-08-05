<?php


namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, [
                'attr' => [
                    'placeholder' => "Username",
                    'autofocus' => "true"
                ]
            ])
            ->add('_password', PasswordType::class, [
                'attr' => [
                    'placeholder' => "Parolă"
                ]
            ])
            ->add("submit", SubmitType::class, [
                'label' => "Autentificați-vă",
                'attr' => [
                    "class" => "button"
                ]
            ]);
    }
}
