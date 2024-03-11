<?php

namespace App\Controller\Admin;

use App\Entity\Boutique;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BoutiqueCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Boutique::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom de laboutique'), 
            TextField::new('address', 'Adresse'),
            TextField::new('telephone', 'Numéro de télephone'),
            ImageField::new( 'illustration', 'Image representative de notre Boutique' )
                             ->setBasePath( 'illustrations/' )
                             ->setUploadDir( 'public/illustrations/' )
                             ->setUploadedFileNamePattern( '[randomhash].[extension]' )
                             ->setRequired(false),
        ];
    }
}
