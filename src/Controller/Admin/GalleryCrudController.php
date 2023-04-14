<?php

namespace App\Controller\Admin;

use App\Entity\Gallery;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\HttpFoundation\Response; // Ajout de l'import pour la classe Response
use Symfony\Component\Validator\Constraints\Image; // Ajout de l'import pour la classe Image

class GalleryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Gallery::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            ImageField::new('image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                // ->setFormTypeOption('constraints', [ // Ajout des contraintes de validation d'image
                //     new Image([
                //         'maxSize' => '5M', // Taille maximale de l'image
                //         'mimeTypes' => ['image/jpeg', 'image/png', 'image/gif'], // Types de fichiers autorisés
                //         'mimeTypesMessage' => 'Veuillez uploader une image au format JPG, PNG ou GIF', // Message d'erreur en cas de type de fichier invalide
                //     ])
                // ]),
        ];
    }
}
