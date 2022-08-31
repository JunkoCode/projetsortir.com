<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Array_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie')]
#[IsGranted('ROLE_ACTIF')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'afficher_liste_sorties', methods: ['GET'])]
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/listSorties.html.twig', [
            'sorties' => $sortieRepository->findAll(),
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
            $etat = $etatRepository->findOneBy(['libelle' => Etat::CREEE]);

            $sortie->setEtat($etat);
            //dd($sortie);

            $sortieRepository->add($sortie, true);
            $this->addFlash('success', 'Sortie créée!');

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
        return $this->render('sortie/afficherSortie.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/ajouteParticipant/{id}', name: 'ajouter_participant_sortie', methods: ['GET', 'POST'])]
    public function showSortie(Sortie $sortie,EntityManagerInterface $entityManager): Response
    {
        $userConnecte=$this->getUser();
        $nbInscrits=$sortie->getParticipants()->count();
        $datenow = new \DateTimeImmutable("now");

        if($sortie->getDateLimiteInscription() > $datenow){
            $this->addFlash('error',"La date limite d'inscriptions est dépassé");
        } elseif ($nbInscrits >= $sortie->getNombreInscriptionMax()){
            $this->addFlash('error',"Le nombre maximale d'inscriptions est atteinte.");
        } elseif ($sortie->getParticipants()->contains($userConnecte)){
            $this->addFlash('error', "L'utilisateur est déjà inscrit à cette sortie");
        } else{
            $sortie->addParticipant($userConnecte);
            //rajout des lignes pour persister l'information et flushé l'info dans la base
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Amusez vous bien!');
        }

        // La version précédente est une amélioration du code pour éviter la redondance des contrôles de la version en commentaire :
        /*if($sortie->getParticipants()->contains($userConnecte) &&
            $nbInscrits < $sortie->getNombreInscriptionMax() &&
            $sortie->getDateLimiteInscription() > $datenow)
        {
            $sortie->addParticipant($userConnecte);
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Amusez vous bien!');

        } else if ($sortie->getParticipants()->contains($userConnecte) &&
            $nbInscrits < $sortie->getNombreInscriptionMax() &&
            $sortie->getDateLimiteInscription() > $datenow){
            $this->addFlash('error', "L'utilisateur est déjà inscrit à cette sortie");

        } else if (!$sortie->getParticipants()->contains($userConnecte)&&
            $nbInscrits >= $sortie->getNombreInscriptionMax() &&
            $sortie->getDateLimiteInscription() > $datenow){
            $this->addFlash('error',"Le nombre maximale d'inscriptions est atteinte.");
        } else {
            $this->addFlash('error',"La date limite d'inscriptions est dépassé");
        }*/

        //$participants=$sortie->getParticipants();

        return $this->redirectToRoute('afficher_sortie', ['id'=>$sortie->getId()]);

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
