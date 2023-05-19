<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\GalleryRepository;


class GalleryController extends AbstractController
{
    #[Route('/', name: 'app_gallery')]
    public function index(GalleryRepository $galleryRepository): Response
    {
        $lastImages = $galleryRepository->findAll();
        return $this->render('gallery/index.html.twig', [
            'lastImages' => $lastImages,
        ]);
    }
}
