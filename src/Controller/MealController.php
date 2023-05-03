<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Repository\MealRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MealController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/menu', name: 'app_meals')]
    public function index(MealRepository $mealRepository): Response
    {
        $meals = $mealRepository->findAll();
        return $this->render('meal/index.html.twig', [
            'meals' => $meals,
        ]);
    }
    #[Route('/menu/{slug}', name: 'app_meal')]
    public function show($slug)
    {
        $meal = $this->entityManager->getRepository(Meal::class)->findOneBySlug($slug);
        if (!$meal) {
            return $this->redirectToRoute('app_meals');
        }

        return $this->render('meal/show.html.twig', [
            'meal' => $meal,
        ]);
    }
}
