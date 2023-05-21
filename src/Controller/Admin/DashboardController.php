<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\Category;
use App\Entity\User;
use App\Entity\Hours;
use App\Entity\Gallery;
use App\Entity\MaxGuests;
use App\Entity\Meal;
use App\Entity\RestaurantCard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')] #changement temporaire
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Le Quai Antique');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Horaires', 'fas fa-clock', Hours::class);
        yield MenuItem::linkToCrud('Galeries', 'fa-solid fa-image', Gallery::class);
        yield MenuItem::linkToCrud('Places disponibles', 'fas fa-user-minus', MaxGuests::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list', Category::class);
        yield MenuItem::linkToCrud('Repas', 'fa-solid fa-kitchen-set', Meal::class);
        yield MenuItem::linkToCrud('Formule/carte', 'fa-regular fa-map', RestaurantCard::class);
        yield MenuItem::linkToCrud('Réservation', 'fa-solid fa-utensils', Booking::class);
    }
}
