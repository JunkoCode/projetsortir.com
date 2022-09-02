<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Services\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{

    #[Route('/profil/{id}', name: 'Profil')]
    public function showProfil($id, UtilisateurRepository $utilisateurRepository): Response
    {

        $utilisateur = $utilisateurRepository->find($id);

        return $this->render('utilisateur/profil.html.twig', [
            "utilisateur" => $utilisateur
        ]);
    }

    #[Route('/profilParticipant/{id}', name: 'ProfilParticipant')]
    public function showProfilParticipant($id, UtilisateurRepository $utilisateurRepository): Response
    {
        $userConnecte = $this->getUser();
        $participant = $utilisateurRepository->find($id);

        if ($userConnecte === $participant) {
            return $this->render('utilisateur/profil.html.twig', [
                "utilisateur" => $userConnecte]);
        } else {
            return $this->render('utilisateur/profil.html.twig', [
                "utilisateur" => $participant]);
        }
    }


    #[Route('/updateprofil/{id}', name: 'UpdateProfil')]
    public function updateProfil(Request $request, Utilisateur $utilisateur, UtilisateurRepository $utilisateurRepository, FileUploader $fileUploader,UserPasswordHasherInterface $userPasswordHasher): Response
    {

        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        $campusBefore = $utilisateur->getCampus();
        $password = $utilisateur->getPassword();

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur->setCampus($campusBefore);

            $newPassword = $form->get("nouveau")->getData();

            /**@var UploadedFile $avatarFile */
            $avatarFile = $form->get('photo')->getData();
            if ($avatarFile) {
                $avatarFileName = $fileUploader->upload($avatarFile);
                $utilisateur->setPhoto($avatarFileName);
            }

            if ($newPassword){
                $utilisateur->setPassword(
                    $userPasswordHasher->hashPassword(
                        $utilisateur,
                        $form->get('nouveau')->getData()
                    )
                );
            }



            $utilisateurRepository->add($utilisateur, true);

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('utilisateur/updateprofil.html.twig', [
            'utilisateur' => $utilisateur,
            'updateForm' => $form,
        ]);
    }

}
