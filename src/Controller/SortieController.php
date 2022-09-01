<?php

namespace App\Controller;

use App\data\FiltreData;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\SortieFiltreType;
use App\Form\SortieType;
use App\Form\SortieTypeAjax;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use DateInterval;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie')]
#[IsGranted('ROLE_ACTIF')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'afficher_liste_sorties', methods: ['GET', 'POST'])]
    public function index(SortieRepository $sortieRepository, Request $request): Response
    {
        $data = new FiltreData();
        $form = $this->createForm(SortieFiltreType::class, $data);
        $form->handleRequest($request);
        $idUser = $this->getUser()->getId();
        /*todo : rajouter méthode pour mettre à jour l'état des sorties*/

        $sorties= $sortieRepository->findByFiltre($idUser,$data);


        return $this->render('sortie/listSorties.html.twig', [
            'sorties' => $sorties,
            'formFilter' => $form->createView()
        ]);
    }

    #[Route('/creer', name: 'creer_sortie', methods: ['GET', 'POST'])]
    public function creerSortie(Request $request, SortieRepository $sortieRepository, EtatRepository $etatRepository, LieuRepository $lieuRepository): Response
    {
        $sortie = new Sortie();
        $form = $this->createForm(SortieTypeAjax::class, $sortie);
        $form->handleRequest($request);

        $lieu = new Lieu();
        $formLieu = $this->createForm(LieuType::class, $lieu);
        $formLieu->handleRequest($request);

        if ($formLieu->isSubmitted() && $formLieu->isValid()) {
            $lieuRepository->add($lieu, true);
            $this->addFlash('success', 'Lieu créée!');
            return $this->renderForm('sortie/creerSortie.html.twig', [
                'sortie' => $sortie,
                'form' => $form,
                'formLieu' =>$formLieu,
            ]);
        }

        if($form->isSubmitted() && $form->isValid()) {

            $dureeEnMinutes = $form->get('duree')->getData() * 60;

            if (($form->get('latitude')->getData() != null)) {
                $sortie->getLieu()->setLatitude($form->get('latitude')->getData());
            }

            if (($form->get('longitude')->getData() != null)) {
                $sortie->getLieu()->setLongitude($form->get('longitude')->getData());
            }

            $sortie->setDuree(new DateInterval('PT' . $dureeEnMinutes . 'M'));
            $sortie->setOrganisateur($this->getUser());

            if ($form->getClickedButton() && 'btnEnregistrer' === $form->getClickedButton()->getName()) {
                $sortie->setEtat($etatRepository->findOneBy(['libelle' => Etat::ETAT_CREEE]));
            }

            if ($form->getClickedButton() && 'btnPublier' === $form->getClickedButton()->getName()) {
                $sortie->setEtat($etatRepository->findOneBy(['libelle' => Etat::ETAT_OUVERTE]));
            }

            $sortieRepository->add($sortie, true);
            $this->addFlash('success', 'Sortie créée!');

            return $this->redirectToRoute('afficher_liste_sorties', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/creerSortie.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
            'formLieu' =>$formLieu,
        ]);
    }


    #[Route('/{id}', name: 'afficher_sortie', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {
        return $this->render('sortie/afficherSortie.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/ajouteParticipant/{id}', name: 'ajouter_participant_sortie', methods: ['GET', 'POST'])]
    public function showSortieParticiper(Sortie $sortie, EntityManagerInterface $entityManager, EtatRepository $etatRepository): Response
    {
        $userConnecte = $this->getUser();
        $nbInscrits = $sortie->getParticipants()->count();
        $datenow = new DateTimeImmutable("now");

        if ($sortie->getEtat() !== $etatRepository->findOneBy(['libelle' => Etat::ETAT_OUVERTE])) {
            $this->addFlash('danger', "La participation à la sortie n'est plus ouverte.");
        } elseif ($sortie->getDateLimiteInscription() < $datenow) {
            $this->addFlash('danger', "La date limite d'inscriptions est dépassé");
        } elseif ($nbInscrits >= $sortie->getNombreInscriptionMax()) {
            $this->addFlash('danger', "Le nombre maximale d'inscriptions est atteinte.");
        } elseif ($sortie->getParticipants()->contains($userConnecte)) {
            $this->addFlash('danger', "L'utilisateur est déjà inscrit à cette sortie");
        } else {
            $sortie->addParticipant($userConnecte);
            //rajout des lignes pour persister l'information et flushé l'info dans la base
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Amusez vous bien!');
        }

        return $this->redirectToRoute('afficher_sortie', ['id' => $sortie->getId()]);

    }

    #[Route('/retirerParticipant/{id}', name: 'retirer_participant_sortie', methods: ['GET', 'POST'])]
    public function showSortieRetirer(Sortie $sortie, EntityManagerInterface $entityManager, EtatRepository $etatRepository): Response
    {
        $userConnecte = $this->getUser();
        //vérifier l'état de la sortie
        if ($sortie->getEtat() !== $etatRepository->findOneBy(['libelle' => Etat::ETAT_OUVERTE])) {
            $this->addFlash('danger', "Impossible de se retirer de la sortie car la sortie est ".strtolower($sortie->getEtat()->getLibelle()));
        } elseif (!$sortie->getParticipants()->contains($userConnecte)) {
            $this->addFlash('danger', "L'utilisateur n'est pas inscrit à cette sortie");
        } else {
            $sortie->removeParticipant($userConnecte);
            //rajout des lignes pour persister l'information et flushé l'info dans la base
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('warning', 'Vous avez été retiré des participants!');
        }

        return $this->redirectToRoute('afficher_sortie', ['id' => $sortie->getId()]);
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

    #[Route('/annulerSortieOrganisateur/{id}', name: 'annuler_sortie_organisateur', methods: ['GET','POST'])]
    public function delete(Request $request, Sortie $sortie, EtatRepository $etatRepository, EntityManagerInterface $entityManager): Response
    {

        $datenow = new DateTimeImmutable("now");
        $userConnecte = $this->getUser();

        if($userConnecte!==$sortie->getOrganisateur()){
            $this->addFlash('danger', "Suppression impossible, vous n'êtes pas l'organisateur de cette sortie.");
        } elseif ($datenow >= $sortie->getDateHeureDebut()){
            $this->addFlash('danger',"La sortie a débuté, impossible de le supprimer.");
        } else {
            $sortie->setEtat($etatRepository->findOneBy(['libelle'=> Etat::ETAT_ANNULEE]));
            if($sortie->getEtat()===$etatRepository->findOneBy(['libelle'=> Etat::ETAT_ANNULEE])){
                $sortie->setInfoSortie('Sortie annulée');
            }
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('warning', 'La sortie a été annulé!');
        }

        return $this->redirectToRoute('afficher_liste_sorties', [], Response::HTTP_SEE_OTHER);
    }
}
