<?php

namespace App\Controller\Admin;

use App\Entity\Slider;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slider::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            ImageField::new('photo')->setUploadDir('public/assets/img/carousel/')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->onlyWhenCreating(),
            ImageField::new('photo')->setUploadDir('public/assets/img/carousel/')->setUploadedFileNamePattern('[slug]-[timestamp].[extension]')->onlyWhenUpdating()->setFormTypeOptions([
                'required' => false,
            ]),
            ImageField::new('photo')->setBasePath('assets/img/carousel/')->hideOnForm(),
            ChoiceField::new('ordre')->setChoices([
                '1' => '1',
                '2' => '2',
                '3' => '3',
            ])
                ->setFormTypeOption('expanded', true),
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
