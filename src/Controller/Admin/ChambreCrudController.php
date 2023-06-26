<?php

namespace App\Controller\Admin;

use App\Entity\Chambre;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ChambreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Chambre::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextEditorField::new('description_courte'),
            TextEditorField::new('description_longue')->hideOnDetail(),
            ImageField::new('photo')->setUploadDir('public/assets/img/chambres/')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->onlyWhenCreating(),
            ImageField::new('photo')->setUploadDir('public/assets/img/chambres/')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->onlyWhenUpdating()->setFormTypeOptions([
                'required' => false,
            ]),
            ImageField::new('photo')->setBasePath('assets/img/chambres/')->hideOnForm(),
            NumberField::new('prix_journalier'),
            DateTimeField::new('date_enregistrement')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        $produit = new $entityFqcn;
        $produit->setDateEnregistrement(new \DateTime);
        return $produit;
    }
}
