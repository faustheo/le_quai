<?php

namespace App\Controller;

use App\Repository\HoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HoursController extends AbstractController
{
    #[Route('/hours', name: 'app_hours')]
    public function index(HoursRepository $hoursRepository): Response
    {
        $lastDatesAndHours = $hoursRepository->findLastDatesAndHours(7);
        $openingHours = [];
        $closingHours = [];
        foreach ($lastDatesAndHours as $hour) {
            $openingHours[$hour['date']->format('Y-m-d')] = [
                'lunch' => $hour['LunchOpening']->format('H:i'),
                'dinner' => $hour['DinnerOpening']->format('H:i'),
            ];
            $closingHours[$hour['date']->format('Y-m-d')] = [
                'lunch' => $hour['LunchClosing']->format('H:i'),
                'dinner' => $hour['DinnerClosing']->format('H:i'),
            ];
        }
        $lastDatesAndHours = $hoursRepository->findLastDatesAndHours(7);
        $times = []; // Ajoute ici la génération des tranches horaires
        return $this->render('hours/index.html.twig', [
            'lastDatesAndHours' => $lastDatesAndHours,
            'times' => $times,
        ]);
    }
}
