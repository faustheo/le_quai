<?php

namespace App\Controller;

use App\Repository\MaxGuestsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MaxGuestsController extends AbstractController
{
    #[Route('/max/guests', name: 'app_max_guests')]
    public function getAvailableSeats(MaxGuestsRepository $maxGuestsRepository): JsonResponse
    {
        $availableSeats = $maxGuestsRepository->getAvailableSeats();

        return new JsonResponse(['available_seats' => $availableSeats]);
    }
}
