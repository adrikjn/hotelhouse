<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Entity\Chambre;
use App\Entity\Commande;
use App\Entity\Newsletter;
use App\Form\CommandeType;
use jcobhams\NewsApi\NewsApi;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Mime\Email;
use App\Repository\SliderRepository;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    #[Route('/newsletter', name: 'newsletter')]
    public function inscriptionNewsletter(Request $request, MailerInterface $mailer, EntityManagerInterface $manager): Response
    {
        $email = $request->request->get('email');
    
        $user = $manager->getRepository(Newsletter::class)->findOneBy(['email' => $email]);
    
        if (!$user) {
            $user = new Newsletter();
            $user->setEmail($email);
            $user->setAbonne(true);
            $manager->persist($user);
            $manager->flush();
        }
    
        $newsletterEmail = (new TemplatedEmail())
            ->from('adrien.kouyoumjian@outlook.fr')
            ->to($email)
            ->subject('Inscription Newsletter')
            ->htmlTemplate('emails/newsletter.html.twig');
          
    
        $mailer->send($newsletterEmail);
    
        $this->addFlash('success', 'Vous êtes inscrit à la Newsletter');
        return $this->redirectToRoute('home');
    }
    
   


    #[Route('/message', name: 'message')]
    public function sendMessage(Request $request, MailerInterface $mailer): Response
    {
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $email = $request->request->get('email');
        $sujet = $request->request->get('sujet');
        $categorie = $request->request->get('categorie');
        $message = $request->request->get('message');

        $email = (new TemplatedEmail())
            ->from($email)
            ->to('adrien.kouyoumjian@outlook.fr')
            ->subject('Nouveau message')
            ->htmlTemplate('emails/message.html.twig')
            ->context([
                'nom' => $nom,
                'prenom' => $prenom,
                'recipient_email' => $email,
                'sujet' => $sujet,
                'categorie' => $categorie,
                'message' => $message,
            ]);

        $mailer->send($email);

        $this->addFlash('success', 'Votre message a été envoyé avec succès.');

        return $this->redirectToRoute('home');
    }
    #[Route('/pds', name: 'plan')]
    public function plan(): Response
    {
        return $this->render('app/plan.html.twig');
    }
}
