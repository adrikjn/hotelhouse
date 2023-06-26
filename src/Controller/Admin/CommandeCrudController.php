<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            AssociationField::new('chambre')->onlyWhenUpdating(),
            AssociationField::new('chambre')->onlyOnIndex(),
            DateField::new('date_arrive')->setFormat('dd/MM/yyyy')->onlyWhenUpdating(),
            DateField::new('date_arrive')->setFormat('dd/MM/yyyy')->onlyOnIndex(),
            DateField::new('date_depart')
                ->setFormat('dd/MM/yyyy')
                ->onlyWhenUpdating()
                ->setFormTypeOptions([
                    'constraints' => [
                        new \Symfony\Component\Validator\Constraints\Callback(function ($value, $context) {
                            $form = $context->getRoot();
                            $dateArrive = $form->get('date_arrive')->getData();

                            if ($value < $dateArrive) {
                                $context->addViolation('La date de départ doit être postérieure à la date d\'arrivée.');
                            }
                        }),
                    ],
                ]),
            DateField::new('date_depart')->setFormat('dd/MM/yyyy')->onlyOnIndex(),
            NumberField::new('prix_total'),
            TextField::new('prenom')->onlyOnIndex(),
            TextField::new('nom')->onlyOnIndex(),
            TelephoneField::new('telephone')->onlyOnIndex(),
            EmailField::new('email')->onlyOnIndex(),
            DateTimeField::new('date_enregistrement')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW);
    }

    public function createEntity(string $entityFqcn)
    {
        $produit = new $entityFqcn;
        $produit->setDateEnregistrement(new \DateTime);
        return $produit;
    }
}
