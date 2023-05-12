<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantCardController extends AbstractController
{
    #[Route('/restaurant/card', name: 'app_restaurant_card')]
    public function index(): Response
    {
        return $this->render('restaurant_card/index.html.twig', [
            'controller_name' => 'RestaurantCardController',
        ]);
    }
}
