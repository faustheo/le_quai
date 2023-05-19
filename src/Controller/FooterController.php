<?php

namespace App\Controller;

use App\Repository\HoursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FooterController extends AbstractController
{
    public function footer(HoursRepository $hoursRepository): Response
    {
        $datesAndHoursAvailable = $hoursRepository->findAllDatesAndHoursAvailable();

        $openingHours = [];
        $closingHours = [];

        foreach ($datesAndHoursAvailable as $date => $hours) {
            [$lunch, $dinner] = $hours['hours'];
            $openingHours[$date] = [
                'lunch' => date('H:i', strtotime(explode(' - ', $lunch)[0])),
                'dinner' => date('H:i', strtotime(explode(' - ', $dinner)[0])),
            ];
            $closingHours[$date] = [
                'lunch' => date('H:i', strtotime(explode(' - ', $lunch)[1])),
                'dinner' => date('H:i', strtotime(explode(' - ', $dinner)[1])),
            ];
        }

        return $this->render('footer/index.html.twig', [
            'openingHours' => $openingHours,
            'closingHours' => $closingHours,
        ]);
    }
}
