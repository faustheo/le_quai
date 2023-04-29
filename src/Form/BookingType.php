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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use DateTime;
use DateInterval;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\FormEvents;


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
            $choices[$dateTime->format('Y-m-d')] = $dateTime;
        }
        
        $builder
        ->add('date', ChoiceType::class, [
            'choices' => $choices,
            'label' => 'Choisissez une date',
            'attr' => [
                'class' => 'js-booking-date', // Ajouter une classe
            ],
            'data' => new \DateTime(), // Valeur par défaut
            'choice_label' => function ($value, $key, $index) {
                /** @var \DateTime $value */
                return $value->format('D - Y-m-d');
            },
        ]);
            $hours = [];
            foreach ($datesAndHoursAvailable as $key => $values) {
                $hoursObj = new \DateTime($key); 
                $lunchHours = explode('-', $values['hours'][0]);
                $dinnerHours = explode('-', $values['hours'][1]);
                $lunchOpening = new \DateTime($lunchHours[0]);
                $lunchClosing = new \DateTime($lunchHours[1]);
                $dinnerOpening = new \DateTime($dinnerHours[0]);
                $dinnerClosing = new \DateTime($dinnerHours[1]);
                $startTime = $lunchOpening;
                $endTime = $dinnerClosing;
                $startTime = $lunchOpening;
                while ($startTime < $lunchClosing) {
                    $hours[$date][$startTime->format('H:i:s')] = $startTime->format('H:i:s');
                    $startTime->modify('+15 minutes');
                }
                
                $startTime = $dinnerOpening;
                while ($startTime < $dinnerClosing) {
                    $hours[$date][$startTime->format('H:i:s')] = $startTime->format('H:i:s');
                    $startTime->modify('+15 minutes');
                }
                
                
                }
            
            $builder->add('hours', ChoiceType::class, [
                'choices' => $hours,
                'label' => 'Heure de réservation',
                'attr' => [
                    'class' => 'js-booking-hours', // Ajouter une classe
                ],
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
                    'Sésame' => 'Sésame',
                    'Arachide' => 'Arachide',
                    'Fruits à coque' => 'Fruits à coque',
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