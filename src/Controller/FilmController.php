<?php

namespace App\Controller;

use App\Entity\Film;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/create', name: 'create')]
    #[Route('/update/{id?1}', name: 'update')]
    public function createFilm(ManagerRegistry $doctrine, HttpFoundationRequest $request, $id = null): Response
    {
        $entityManager = $doctrine->getManager();
        $isEditor= false;

        if(isset($id))
        {
            $films = $entityManager->getRepository(Film::class)->find($id);
            if(!isset($films))
            {
                return $this->redirectToRoute('listing');
            }
            $isEditor= true;
        } else {
            $films = new Film;
        }
        
        $form = $this->createFormBuilder($films)
            ->add("title", TextType::class)
            ->add("realisateur", TextType::class)
            ->add("genre", TextType::class)
            ->add("date", TextType::class)
            ->add("image", null, ['required' => false], TextType::class)
            ->add("save", SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $films = $form->getData();
            $entityManager->persist($films);
            $entityManager->flush();
        }

        return $this->render('film/create.html.twig', [
            'form' => $form->createView(),
            'isEditor' => $isEditor
        ]);
    }

    #[Route('/listing', name:'listing')]
    public function listing(ManagerRegistry $doctrine)
    {
        $films = $doctrine->getManager()->getRepository(Film::class)->findAll();   

       return $this->render('film/listing.html.twig', [
           'films' => $films,
         ]);
    }

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
