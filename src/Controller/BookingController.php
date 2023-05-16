<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Booking;
use App\Form\BookingType;
use App\Repository\HoursRepository;
use App\Repository\MaxGuestsRepository;
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
    private $maxGuestsRepository;

    public function __construct(EntityManagerInterface $entityManager, MaxGuestsRepository $maxGuestsRepository)
    {
        $this->entityManager = $entityManager;
        $this->maxGuestsRepository = $maxGuestsRepository;
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
    public function index(MaxGuestsRepository $maxGuestsRepository): Response
    {
        $user = $this->getUser();

        $booking = new Booking();

        if ($user instanceof User) {
            $booking->setGuests($user->getGuests());
            $booking->setAllergies($user->getAllergy());
        }

        $form = $this->createForm(BookingType::class, $booking);

        $maxGuests = $maxGuestsRepository->findOneByAvailableSeats();
        $availableSeats = $maxGuests ? $maxGuests->getAvailableSeats() : 0;

        return $this->render('booking/index.html.twig', [
            'form' => $form->createView(),
            'available_seats' => $availableSeats,
        ]);
    }


    #[Route("/submit-booking", name: "app_submit_booking", methods: ["POST"])]
    public function submitBooking(Request $request): JsonResponse
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);

        $form->handleRequest($request);
        $maxGuests = $this->maxGuestsRepository->findOneByAvailableSeats();
        $availableSeats = $maxGuests ? $maxGuests->getAvailableSeats() : 0;

        if ($form->isSubmitted() && $form->isValid()) {
            $localHour = $form->get('hours')->getData();

            if ($localHour === null) {
                throw new \RuntimeException("Aucune heure sélectionnée");
            }

            $bookingDate = $booking->getDate();
            $bookingDateTime = new \DateTime($bookingDate->format('Y-m-d') . ' ' . $localHour);

            $booking->setDate($bookingDateTime);
            $booking->setHours($bookingDateTime->format('H:i:s'));

            $this->entityManager->persist($booking);
            $this->entityManager->flush();

            $availableSeats -= $booking->getGuests();
            $maxGuests->setAvailableSeats($availableSeats);
            $this->entityManager->persist($maxGuests);
            $this->entityManager->flush();

            return new JsonResponse(['available_seats' => $availableSeats]);
        } else {
            return new JsonResponse(['error' => 'Invalid form data'], Response::HTTP_BAD_REQUEST);
        }
    }
}
