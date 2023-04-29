<?php

namespace App\Controller;

use App\Form\BookingType;
use App\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class BookingController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reservation', name: 'app_booking')]
    public function index(Request $request): Response
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
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

