<?php

namespace App\Controller;

use App\Entity\Seance;
use App\Form\SeanceType;
use App\Repository\SeanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/seance')]
class SeanceController extends AbstractController
{
    #[Route('/', name: 'listing_seance', methods: ['GET'])]
    public function index(SeanceRepository $seanceRepository): Response
    {
        return $this->render('seance/listing.html.twig', [
            'seances' => $seanceRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create_seance')]
    #[Route('/update/{id?1}', name: 'update_seance')]
    public function new(Seance $seance= null, Request $request, EntityManagerInterface $entityManager): Response
    {
        if(!$seance){
            $seance = new Seance();
        }
        
        $form = $this->createForm(SeanceType::class, $seance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$seance->getId()){
                $seance->setCreatedAt(new \DateTimeImmutable('now'));
            }

            $seance->setUpdatedAt(new \DateTime('now'));

            $seance = $form->getData();
            $entityManager->persist($seance);
            $entityManager->flush();

            return $this->redirectToRoute('listing_seance', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('seance/create.html.twig', [
            'seance' => $seance,
            'form' => $form,
            'isEditor' => $seance->getId()
        ]);
    }

    #[Route('/{id}', name: 'seance_show', methods: ['GET'])]
    public function show(Seance $seance): Response
    {
        return $this->render('seance/show.html.twig', [
            'seance' => $seance,
        ]);
    }

    #[Route('/delete/{id}', name: 'seance_delete',)]
    public function delete(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();

        $seance = $entityManager->getRepository(Seance::class)->find($id);

        if(isset($seance)) {
            $entityManager->remove($seance);
            $entityManager->flush();
        }
        return $this->redirectToRoute('seance_index');
    }
}
