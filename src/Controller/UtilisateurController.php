<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Monolog\Handler\Curl\Util;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    public function userProfil(): Response
    {

        return $this->render('utilisateur/profil.html.twig', [

        ]);
    }


    #[Route('/updateProfil/{id}', name: 'updateProfil')]
    public function updateUser($id, UtilisateurRepository $utilisateurRepository): Response
    {

        //todo : utiliser le formulaire register pour envoyer les modification en base.

        $utilisateur = $utilisateurRepository->find($id);

        return $this->render('utilisateur/updateProfil.html.twig', [
            "utilisateur" => $utilisateur
        ]);
    }

    #[Route('/profil/{id}', name: 'showProfil')]
    public function ShowUser(Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/updateProfil.html.twig', [
            "utilisateur" => $utilisateur
        ]);
    }

}
