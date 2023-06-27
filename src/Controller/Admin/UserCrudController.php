<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    public function __construct(public UserPasswordHasherInterface $hasher)
    {
    }


    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            EmailField::new('email')->onlyOnIndex(),
            EmailField::new('email')->onlyWhenCreating(),
            TextField::new('password', 'Mot de passe')->setFormType(PasswordType::class)->onlyWhenCreating(),
            TextField::new('pseudo')->onlyOnIndex(),
            TextField::new('pseudo')->onlyWhenCreating(),

            TextField::new('nom')->onlyOnIndex(),
            TextField::new('nom')->onlyWhenCreating(),
            TextField::new('prenom')->onlyOnIndex(),
            TextField::new('prenom')->onlyWhenCreating(),
            ChoiceField::new('civilite')->setChoices([
                'Homme' => 'homme',
                'Femme' => 'femme',
            ])
                ->setFormTypeOption('expanded', true)->onlyOnIndex(),
            ChoiceField::new('civilite')->setChoices([
                'Homme' => 'homme',
                'Femme' => 'femme',
            ])
                ->setFormTypeOption('expanded', true)->onlyWhenCreating(),
            DateTimeField::new('dateEnregistrement')->setFormat('d/M/Y à H:m:s')->hideOnForm(),
            CollectionField::new('roles')->setTemplatePath('admin/field/roles.html.twig'),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance->getId()) {
            $entityInstance->setPassword(
                $this->hasher->hashPassword(
                    $entityInstance,
                    $entityInstance->getPassword()
                )
            );
        }
        $entityManager->persist($entityInstance);
        $entityManager->flush();
    }

    public function createEntity(string $entityFqcn)
    {
        $produit = new $entityFqcn;
        $produit->setDateEnregistrement(new \DateTime);
        return $produit;
    }
}
