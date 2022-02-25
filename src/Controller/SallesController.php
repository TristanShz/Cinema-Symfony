<?php

namespace App\Controller;

use App\Entity\Salles;
use App\Form\SallesType;
use App\Repository\SallesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/salles')]
class SallesController extends AbstractController
{
    #[Route('/', name: 'listing_salle', methods: ['GET'])]
    public function index(SallesRepository $sallesRepository): Response
    {
        return $this->render('salles/listing.html.twig', [
            'salles' => $sallesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'create_salle', methods: ['GET', 'POST'])]
    #[Route('/update/{id?1}', name:'update_salle')]
    public function new(Salles $salle= null, Request $request, EntityManagerInterface $entityManager, SallesRepository $salleRepository): Response
    {
        if(!$salle){
        $salle = new Salles();
        }
        $form = $this->createForm(SallesType::class, $salle);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($salle);
            $entityManager->flush(); 
            
            return $this->redirectToRoute('listing_salle', [], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('salles/create.html.twig', [
            'salle' => $salle,
            'form' => $form->createView(),
            'isEditor' => $salle->getId()
        ]);
    }


    #[Route('/{id}', name: 'salle_delete')]
    public function delete(ManagerRegistry $doctrine, $id): Response
    {
        $entityManager = $doctrine->getManager();

        $salle = $entityManager->getRepository(Salles::class)->find($id);

        if(isset($salle)) {
            $entityManager->remove($salle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('listing_salle');
    }
}