<?php

namespace App\Form;

use App\Entity\Booking;
use App\Repository\HoursRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\Choice;
use App\Form\DataTransformer\StringToDateTimeTransformer;

class BookingType extends AbstractType
{
    private $hoursRepository;

    public function __construct(HoursRepository $hoursRepository)
    {
        $this->hoursRepository = $hoursRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $datesAndHoursAvailable = $this->hoursRepository->findAllDatesAndHoursAvailable();

        $choices = [];

        foreach ($datesAndHoursAvailable as $date => $hours) {
            $dateTime = \DateTime::createFromFormat('Y-m-d', $date);
            $dayOfWeek = $dateTime->format('D');
            $dateFormatted = $dateTime->format('d/m/Y');
            $choiceLabel = $dayOfWeek . ' : ' . $dateFormatted;
            $choices[$choiceLabel] = $dateTime->format('Y-m-d');
        }

        $stringToDateTimeTransformer = new StringToDateTimeTransformer();

        $builder
            ->add('date', ChoiceType::class, [
                'choices' => $choices,
                'label' => 'Choisissez une date',
                'attr' => [
                    'class' => 'js-booking-date my-custom-class', // Ajouter une classe
                ],
                'data' => (new \DateTime())->format('Y-m-d'), // Valeur par défaut
            ]);

        $builder
            ->get('date')->addModelTransformer($stringToDateTimeTransformer)

            ->add('hours', ChoiceType::class, [
                'label' => 'Heure de réservation',
                'attr' => [
                    'class' => 'js-booking-hours', // Ajouter une classe
                ],
                'choices' => [] // Laisser vide pour que le JavaScript remplisse les options


            ])
            ->add('guests', IntegerType::class, [
                'label' => 'Le nombre de convives',
                'attr' => [
                    'min' => 1,
                    'max' => $options['max_guests'] // utiliser le maximum de convives autorisés
                ],
            ])
            ->add('allergies', ChoiceType::class, [
                'label' => 'Allergies',
                'required' => false,
                'mapped' => true,
                'choices' => [
                    'Gluten' => 'Gluten',
                    'Oeufs' => 'Oeufs',
                    'Fruits de mer' => 'Fruits de mer',
                    'Celeri' => 'Celeri',
                    'Sesame' => 'Sesame',
                    'Arachide' => 'Arachide',
                    'Fruits a coque' => 'Fruits a coque',
                    'Moutarde' => 'Moutarde',
                    'Soja' => 'Soja',
                    'Lupin' => 'Lupin',
                    'Lait' => 'Lait',
                    'Mollusques' => 'Mollusques',
                    'Poisson' => 'Poisson',
                    'Sulfites' => 'Sulfites',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Réserver"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'max_guests' => 50 // valeur par défaut, à remplacer par le maximum de convives autorisés défini dans le panel d'administration
        ]);
    }
}
