<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
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
    public function updateUser(): Response
    {

        //todo : utiliser le formulaire register pour envoyer les modification en base.
        return $this->render('utilisateur/updateProfil.html.twig', [

        ]);
    }

}
