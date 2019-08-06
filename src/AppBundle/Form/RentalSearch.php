<?php


namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
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
            ->add("date", DateType::class, [
                'data' => new \DateTime(),
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