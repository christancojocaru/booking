<?php


namespace AppBundle\Form;


use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AccommodationSearch extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add("location", TextType::class, [
            'attr' => [
                'placeholder' => 'Unde mergeți?',
                'class' => 'search',
                'autocomplete' => 'off'
            ]
        ])
        ->add("startDate", DateType::class, [
            'data' => new DateTime(),
            'widget' => 'single_text',
            'attr' => [
                'class' => 'search'
            ]
        ])
        ->add("endDate", DateType::class, [
            'data' => new DateTime("tomorrow"),
            'widget' => 'single_text',
            'attr' => [
                'class' => 'search'
            ]
        ])
        ->add("number", TextType::class, [
            'attr' => [
                'placeholder' => 'Câte persoane?',
                'class' => 'number search',
                'onkeydown' => 'down(event, this)'
            ]
        ])
        ->add("submit", SubmitType::class, [
            'label' => "Caută",
            'attr' => [
                'class' => 'send search button'
            ]
        ]);
}
}