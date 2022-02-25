<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/film')]
class FilmController extends AbstractController
{

    //CREATION ET MISES A JOUR DES FILMS 
    #[Route('/create', name: 'create_film')]
    #[Route('/update/{id?1}', name: 'update_film')]
    public function film(Film $film = null, EntityManagerInterface $entityManager, Request $request, FileUploader $fileUploader): Response
    {
        // $entityManager = $doctrine->getManager();
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

            return $this->redirectToRoute('listing_film');
        }

        return $this->render('film/create.html.twig', [
            'film' => $film,
            'form' => $form->createView(),
            'isEditor' => $film->getId()
        ]);
    }

    //LISTING DES FILMS 
    #[Route('/listing', name:'listing_film')]
    public function listing(FilmRepository $filmRepository)
    {
       return $this->render('film/listing.html.twig', [
           'films' => $filmRepository->findAll()
         ]);
    }

    //SUPPRESSION DE FILM
    #[Route('/delete/{id}', name:'delete_film')]
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
