<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'attr' => [
                        'placeholder' => 'Title'
                    ]
                ]
            )
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Select Category'
            ])
            ->add(
                'plot',
                TextareaType::class,
                [
                    'attr' => [
                        'placeholder' => 'Plot'
                    ]
                ]
            )
            ->add(
                'director',
                TextType::class,
                [
                    'attr' => [
                        'placeholder' => 'Director'
                    ]
                ]
            )
            ->add(
                'releasedDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'attr' => array(
                        'min' => date('1950-01-01'),
                        'max' => date('Y-m-d')
                    )
                ]
            )
            ->add(
                'quantity',
                IntegerType::class,
                [
                    'attr' => [
                        'placeholder' => 'Number of copies'
                    ]
                ]
            )
            ->add(
                'poster',
                FileType::class, array(
                    'data_class' => null,
                )
            );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
