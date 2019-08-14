<?php


namespace AppBundle\Form;


use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RentalSearch extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("location", TextType::class, [
                'attr' => [
                    'placeholder' => 'Orasul...',
                    'class' => 'search',
                    'autofocus' => 'true'
                ]
            ])
            ->add("seats", ChoiceType::class, [
                'choices' => [
                    "2" => "2",
                    "5" => "5",
                    "7" => "7",
                    "8" => "8"
                ],
                'attr' => [
                    'class' => 'search'
                ]
            ])
            ->add("fuel", ChoiceType::class, [
                'choices' => [
                    "Benzină" => "benzina",
                    "Ambele" => "false",
                    "Motorină" => "motorina"
                ],
                'attr' => [
                    'class' => 'search'
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
                'data' => new DateTime(),
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'search'
                ]
            ])
            ->add("submit", SubmitType::class, [
                'label' => 'Caută',
                'attr' => [
                    'class' => 'search button'
                ]
            ]);
    }
}