<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Rental;
use DateInterval;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\GreaterThan;

class RentalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $date = new DateTime();
//        $date->add(new DateInterval('P30D'));

        $builder
            ->add(
                'rentalDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'data' => new \DateTime()
                ]
            )

            ->add(
                'returnDate',
                DateType::class,
                [
                    'widget' => 'single_text',
                    'data' => (new \DateTime())->modify('+15 days'),
                    'constraints' => [new GreaterThan('+14 days')],
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rental::class,
        ]);
    }
}
