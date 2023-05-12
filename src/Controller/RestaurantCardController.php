<?php

namespace App\Controller;

use App\Repository\RestaurantCardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RestaurantCardController extends AbstractController
{
    #[Route('/restaurant/card', name: 'app_restaurant_card')]
    public function index(RestaurantCardRepository $restaurantCardRepository): Response
    {
        $findAllRestaurantCards = $restaurantCardRepository->findAll();
        return $this->render('restaurant_card/index.html.twig', [
            'restaurantCards' => $findAllRestaurantCards // Utiliser 'restaurantCards' au lieu de 'findAllRestaurantCard'
        ]);
    }
}
