<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Form\EtatType;
use App\Repository\EtatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/etat')]
class AdminEtatController extends AbstractController
{
    #[Route('/', name: 'app_etat_index', methods: ['GET'])]
    public function index(EtatRepository $etatRepository): Response
    {
        return $this->render('admin/etat/index.html.twig', [
            'etats' => $etatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_etat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EtatRepository $etatRepository): Response
    {
        $etat = new Etat();
        $form = $this->createForm(EtatType::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etatRepository->add($etat, true);

            return $this->redirectToRoute('app_etat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/etat/new.html.twig', [
            'etat' => $etat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etat_show', methods: ['GET'])]
    public function show(Etat $etat): Response
    {
        return $this->render('admin/etat/show.html.twig', [
            'etat' => $etat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etat $etat, EtatRepository $etatRepository): Response
    {
        $form = $this->createForm(EtatType::class, $etat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etatRepository->add($etat, true);
            $this->addFlash('success', "La modification a bien été pris en compte");
            return $this->redirectToRoute('app_etat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/etat/edit.html.twig', [
            'etat' => $etat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_etat_delete')]
    public function delete(Request $request, Etat $etat, EtatRepository $etatRepository): Response
    {

            $etatRepository->remove($etat, true);

        return $this->redirectToRoute('app_etat_index', [], Response::HTTP_SEE_OTHER);
    }
}
