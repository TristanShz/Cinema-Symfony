<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{

    //CREATION ET MISES A JOUR DES FILMS 
    #[Route('/create', name: 'create')]
    #[Route('/update/{id?1}', name: 'update')]
    public function film(Film $film = null, ManagerRegistry $doctrine, HttpFoundationRequest $request, FileUploader $fileUploader): Response
    {
        $entityManager = $doctrine->getManager();

        if (!$film){
            $film = new Film;
        }
        $form = $this->createForm(FilmType::class, $film);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if(!$film->getId()){
                $film->setCreatedAt(new \DateTimeImmutable("now"));
            }
            $film->setUpdatedAt(new \DateTime("now"));

            $imageFile = $form->get('image')->getData();
            if($imageFile){
                $imageFileName = $fileUploader->upload($imageFile);
                $film->setImage($imageFileName);
            }
            $film = $form->getData();
            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('listing');
        }

        return $this->render('film/create.html.twig', [
            'form' => $form->createView(),
            'isEditor' => $film->getId()
        ]);
    }

    //LISTING DES FILMS 
    #[Route('/listing', name:'listing')]
    public function listing(ManagerRegistry $doctrine)
    {
        $films = $doctrine->getManager()->getRepository(Film::class)->findAll();   

       return $this->render('film/listing.html.twig', [
           'films' => $films
         ]);
    }

    //SUPPRESSION DE FILM
    #[Route('/delete/{id}', name:'delete')]
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        if(isset($film)) {
            $entityManager->remove($film);
            $entityManager->flush();
        }
        return $this->redirectToRoute('listing');
    }

    //AFFICHAGE DES DETAILS DE CHAQUE FILMS
    #[Route('/details/{id}', name:'details')]
    public function details(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();

        $film = $entityManager->getRepository(Film::class)->find($id);

        return $this->render('film/details.html.twig', [
            'film' => $film
        ]);
    }
}
