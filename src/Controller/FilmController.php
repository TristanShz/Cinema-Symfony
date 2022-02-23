<?php

namespace App\Controller;

use App\Entity\Film;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/createfilm', name: 'createfilm')]
    public function createFilm(ManagerRegistry $doctrine): Response
    {

        $entityManager = $doctrine->getManager();

        $film = new Film;

        $film->setTitle('Le Labyrinthe');
        $film->setRealisateur('Wes Ball');
        $film->setGenre('Science-fiction post-apocalyptique');

        $entityManager->persist($film);
        $entityManager->flush();

        return new Response('Un nouveau film à été ajouté : ' . $film->getTitle());
    }

    #[Route('/listing', name:'listing')]
    public function listing(ManagerRegistry $doctrine)
    {
        $films = $doctrine->getManager()->getRepository(Film::class)->findAll();   

       return $this->render('film/index.html.twig', [
           'films' => $films,
         ]);
    }

}
