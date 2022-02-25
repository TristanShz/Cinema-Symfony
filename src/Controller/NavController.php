<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use App\Repository\SeanceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class NavController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(FilmRepository $filmRepository, SeanceRepository $seanceRepository) 
    {
        return $this->render("navigation/accueil.html.twig", [
            'films' => $filmRepository->findAll(),
            'seances' => $seanceRepository->findAll()
        ]);
    }

    /**
     * @Route("/homeRedirect", name="redirect")
     */
    public function homeRedirect() 
    {
        return $this->redirectToRoute("accueil");
    }

    
    
}

