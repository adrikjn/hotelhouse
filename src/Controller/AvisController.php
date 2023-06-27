<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AvisController extends AbstractController
{
    #[Route('/avis', name: 'avis')]
    public function index(Request $request, EntityManagerInterface $manager, AvisRepository $repo): Response
    {
        $comments = $repo->findAll();
        $comments = array_reverse($comments);

        $avis = new Avis;

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avis->setDateEnregistrement(new \DateTime);
            $manager->persist($avis);
            $manager->flush();
            $this->addFlash('success', "Votre avis a bien été envoyé");
            return $this->redirectToRoute('avis');
        }

        return $this->render('avis/index.html.twig', [
            'form' => $form,
            'avis' => $comments
        ]);
    }
}
