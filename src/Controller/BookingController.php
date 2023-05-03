<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\HoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BookingController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/get-opening-hours/{date}", name="get_opening_hours", methods={"GET"})
     */
    public function getOpeningHoursAction(string $date, HoursRepository $hoursRepository): JsonResponse
    {
        $dateObject = \DateTime::createFromFormat('Y-m-d', $date);
        $dateImmutable = \DateTimeImmutable::createFromMutable($dateObject); // Convertir en DateTimeImmutable
        $openingAndClosingHours = $hoursRepository->findOpeningAndClosingHoursForDate($dateImmutable);

        if ($openingAndClosingHours === null) {
            return new JsonResponse(['message' => 'Aucune heure trouvee pour cette date.'], Response::HTTP_NOT_FOUND);
        }

        $formattedHours = [
            'lunchOpening' => $openingAndClosingHours['LunchOpening']->format('H:i'),
            'lunchClosing' => $openingAndClosingHours['LunchClosing']->format('H:i'),
            'dinnerOpening' => $openingAndClosingHours['DinnerOpening']->format('H:i'),
            'dinnerClosing' => $openingAndClosingHours['DinnerClosing']->format('H:i')
        ];

        return new JsonResponse($formattedHours);
    }

    #[Route('/reservation', name: 'app_booking')]
    public function index(Request $request): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $localHour = $form->get('hours')->getData();

            if ($localHour === null) {
                // Vous pouvez ajouter un message d'erreur ici ou gérer cette situation comme vous le souhaitez
                throw new \RuntimeException("Aucune heure sélectionnée");
            }

            $bookingDate = $booking->getDate();
            $bookingDateTime = new \DateTime($bookingDate->format('Y-m-d') . ' ' . $localHour);

            $booking->setDate($bookingDateTime);
            $booking->setHours($bookingDateTime->format('H:i:s')); // Définir l'heure avec la chaîne de caractères

            $this->entityManager->persist($booking);
            $this->entityManager->flush();

            $this->addFlash('success', 'La réservation a été effectuée avec succès!');
            $booking = new Booking(); // Créer une nouvelle instance de Booking
            $form = $this->createForm(BookingType::class, $booking); // Recréer le formulaire avec la nouvelle instance
        }

        return $this->render('booking/index.html.twig', [
            'form' => $form->createView(), // Crée la vue du formulaire
        ]);
    }
}
