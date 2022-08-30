<?php

namespace App\Controller;

use App\data\FiltreData;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieFiltreType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'afficher_liste_sorties', methods: ['GET','POST'])]
    public function index(SortieRepository $sortieRepository, Request $request): Response
    {
        $data = new FiltreData();
        $form = $this->createForm(SortieFiltreType::class, $data);
        $form -> handleRequest($request);
        $idUser = $this->getUser()->getId();
        $user = $this->getUser();

        $sorties= $sortieRepository->findByFiltre($user,$idUser,$data);

        return $this->render('sortie/listSorties.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView()
        ]);


    }

    #[Route('/creer', name: 'creer_sortie', methods: ['GET', 'POST'])]
    public function creerSortie(Request $request, SortieRepository $sortieRepository, EtatRepository $etatRepository): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dureeEnMinutes = $form->get('duree')->getData() * 60;

            if (($form->get('latitude')->getData() != null)) {
                $sortie->getLieu()->setLatitude($form->get('latitude')->getData());
            }

            if (($form->get('longitude')->getData() != null)) {
                $sortie->getLieu()->setLongitude($form->get('longitude')->getData());
            }

            $sortie->setDuree(new \DateInterval('PT' . $dureeEnMinutes . 'M'));
            $sortie->setOrganisateur($this->getUser());
            /*if ($form->submit('btnEnregistrer')->isSubmitted()){

            }*/
            $etat=$etatRepository->findOneBy(['libelle'=>Etat::CREEE]);

            $sortie->setEtat($etat);
            //dd($sortie);

            $sortieRepository->add($sortie, true);
            $this->addFlash('success','Sortie créée!');

            return $this->redirectToRoute('afficher_liste_sorties', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/creerSortie.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'afficher_sortie', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/AfficherSortie.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/{id}/edit', name: 'editer_sortie', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortieRepository->add($sortie, true);

            return $this->redirectToRoute('afficher_liste_sorties', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/editerSortie.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'supprimer_sortie', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, SortieRepository $sortieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $sortie->getId(), $request->request->get('_token'))) {
            $sortieRepository->remove($sortie, true);
        }

        return $this->redirectToRoute('afficher_liste_sorties', [], Response::HTTP_SEE_OTHER);
    }


}
