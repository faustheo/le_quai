<?php

namespace App\Form;

use DateTime;
use App\Entity\Booking;
use App\Repository\HoursRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use App\Form\DataTransformer\StringToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class BookingType extends AbstractType
{
    private $hoursRepository;

    public function __construct(HoursRepository $hoursRepository)
    {
        $this->hoursRepository = $hoursRepository;
    }
    private function generateTimeSlots(array $openingAndClosingHours): array
    {
        $lunchOpening = new \DateTimeImmutable($openingAndClosingHours['LunchOpening']);
        $lunchClosing = new \DateTimeImmutable($openingAndClosingHours['LunchClosing']);
        $dinnerOpening = new \DateTimeImmutable($openingAndClosingHours['DinnerOpening']);
        $dinnerClosing = new \DateTimeImmutable($openingAndClosingHours['DinnerClosing']);




        $timeSlots = [];

        // Pour le déjeuner
        for ($time = clone $lunchOpening; $time < $lunchClosing; $time->modify('+30 minutes')) {
            $timeSlots[$time->format('H:i')] = $time->format('H:i');
        }

        // Pour le dîner
        for ($time = clone $dinnerOpening; $time < $dinnerClosing; $time->modify('+30 minutes')) {
            $timeSlots[$time->format('H:i')] = $time->format('H:i');
        }

        return $timeSlots;
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
                    'class' => 'js-booking-date my-custom-class',
                ],
                'data' => (new \DateTime())->format('Y-m-d'), // Valeur par défaut
                'choice_label' => function ($choiceValue, $key, $value) {
                    $dateTime = \DateTime::createFromFormat('Y-m-d', $choiceValue);
                    return $dateTime->format('D - d-m-Y');
                },
                'mapped' => false,
            ]);
        $builder

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $booking = $event->getData();

                // Récupére la valeur du champ "date" et la convertir en objet DateTime
                $dateString = $form->get('date')->getData();
                $date = \DateTime::createFromFormat('Y-m-d', $dateString);

                // Défini la propriété "date" de l'entité Booking avec l'objet DateTime
                $booking->setDate($date);
            });

        // Récupérer les horaires d'ouverture et de fermeture pour la date actuelle
        $today = new \DateTimeImmutable();
        $openingAndClosingHours = $this->hoursRepository->findOpeningAndClosingHoursForDate($today);

        // Générer les tranches horaires en fonction des horaires d'ouverture et de fermeture
        $timeSlots = $this->generateTimeSlots($openingAndClosingHours);

        $builder
            ->add('hours', ChoiceType::class, [
                'label' => 'Heure de réservation',
                'attr' => [
                    'class' => 'js-booking-hours',
                ],
                'choices' => $timeSlots,
                // ...
            ])
            ->add('selectedDate', HiddenType::class, [
                'data' => $today->format('Y-m-d'),
                'mapped' => false,
            ]);



        $builder
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
