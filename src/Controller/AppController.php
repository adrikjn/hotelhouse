<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Form\CommandeType;
use jcobhams\NewsApi\NewsApi;
use Symfony\Component\Mime\Email;
use App\Repository\SliderRepository;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(SliderRepository $repo): Response
    {
        $slides = $repo->findBy(['ordre' => [1, 2, 3]], ['ordre' => 'ASC']);

        return $this->render('app/index.html.twig', [
            'slides' => $slides
        ]);
    }

    #[Route('/chambres', name: 'rooms')]
    public function showRooms(ChambreRepository $repo): Response
    {
        $rooms = $repo->findAll();
        return $this->render('app/rooms.html.twig', [
            'rooms' => $rooms
        ]);
    }

    #[Route('/hotel', name: 'hotel')]
    public function hotelInfo(): Response
    {

        return $this->render('app/hotel.html.twig');
    }

    #[Route('/spa', name: 'spa')]
    public function spa(): Response
    {

        return $this->render('app/spa.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {

        return $this->render('app/contact.html.twig');
    }

    #[Route('/restaurant', name: 'restaurant')]
    public function restaurant(): Response
    {

        return $this->render('app/restaurant.html.twig');
    }

    #[Route('/actualites', name: 'actualites')]
    public function actualites(): Response
    {
        $newsapi = new NewsApi('2910f1c91ef4401b905b5f356f55e533');
        $response = $newsapi->getEverything('hôtellerie', null, null, null, null, null, 'fr', 'publishedAt', 15, null);

        // dd($newsapi->getSortBy());
        // $newsapi->getEverything($q, $sources, $domains, $exclude_domains, $from, $to, $language, $sort_by,  $page_size, $page);

        $articles = $response->articles;
        // dd($articles);

        return $this->render('app/actualites.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/mentions', name: 'ml')]
    public function mentions(): Response
    {

        return $this->render('app/mentions.html.twig');
    }

    #[Route('/cgdv', name: 'cgdv')]
    public function cgdv(): Response
    {

        return $this->render('app/cgdv.html.twig');
    }

    #[Route('/chambre/{id}', name: 'rent')]
    public function rentRoom(Request $request, EntityManagerInterface $manager, Chambre $chambre = null): Response
    {
        if ($chambre === null) {
            return $this->redirectToRoute('home');
        }

        $commande = new Commande;

        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $commande->setDateEnregistrement(new \DateTime)
                ->setChambre($chambre)
                ->setPrixTotal($commande->calculerPrixTotal());
            $manager->persist($commande);
            $manager->flush();
            $this->addFlash('success', "Votre commande a bien été pris");
            return $this->redirectToRoute('home');
        }

        return $this->render('app/rent.html.twig', [
            'form' => $form,
            'chambre' => $chambre
        ]);
    }
    
    // public function sendEmailAction()
    // {
    //     $email = (new Email())
    //         ->from('adrien.kouyoumjian@outlook.fr')
    //         ->to('adrien.kouyoumjian@outlook.fr')
    //         ->subject('Subject of the email')
    //         ->text('Plain text body of the email')
    //         ->html('<p>HTML body of the email</p>');

    //     $this->mailer->send($email);

        
    //     return $this->render('email/success.html.twig');
    // }

    

    #[Route('/newsletter', name: 'newsletter')]
    public function inscription(Request $request, MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('adrien.kouyoumjian@outlook.fr')
            ->to($request->request->get('email'))
            ->subject('Inscription Newsletter')
            ->text("Coucou t'es inscrit")
            ->html('<h2> inscrit  </h2>');
        
            $mailer->send($email);

        // dd($request->request->get('email'));
        return $this->redirectToRoute('home');
    }
}
