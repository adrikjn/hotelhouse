<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Entity\User;
use App\Entity\Slider;
use App\Entity\Chambre;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    public function __construct(private AdminUrlGenerator $adminUrlGenerator){

    }

    #[Route('/admin/personnavatrouveahahahahahalol1245a8eaze9/mdr/', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ChambreCrudController::class)->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Hotelhouse');
    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
        return [
            MenuItem::section('Nos chambres'),
            MenuItem::linkToCrud("Chambres", "fas fa-bed", Chambre::class),
            MenuItem::section('Commandes utilisateurs'),
            MenuItem::linkToCrud("Commande", 'far fa-calendar', Commande::class),
            MenuItem::section("Utilisateurs"),
            MenuItem::linkToCrud('User', 'fas fa-users', User::class),
            MenuItem::section('Caroussel'),
            MenuItem::linkToCrud('Slider', 'fas fa-image', Slider::class),
            MenuItem::section('Commentaires'),
            MenuItem::linkToCrud('Avis', 'fas fa-comment', Avis::class),
            MenuItem::section('Retour au site'),
            MenuItem::linkToRoute('Accueil du site', 'fa fa-igloo', 'home'),
        ];
    }

}
