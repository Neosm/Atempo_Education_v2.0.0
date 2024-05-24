<?php

namespace App\Controller\SuperAdmin;

use App\Entity\Schools;
use App\Form\SchoolType;
use App\Repository\SchoolsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcolesController extends AbstractController
{
    #[Route('/superadmin/ecoles', name: 'superadmin_app_ecole')]
    public function index(SchoolsRepository $schoolsRepository): Response
    {
        // Récupérer la liste des écoles à afficher dans le template
        $ecoles = $schoolsRepository->findAll();

        return $this->render('superAdmin/ecoles/index.html.twig', [
            'controller_name' => 'EcolesController',
            'ecoles' => $ecoles,
        ]);
    }

    #[Route('/superadmin/ecoles/ajouter', name: 'superadmin_app_ecole_ajouter')]
    public function ajouterEcole(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ecole = new Schools();
        $form = $this->createForm(SchoolType::class, $ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ecole);
            $entityManager->flush();

            return $this->redirectToRoute('superadmin_app_ecole');
        }

        return $this->render('superAdmin/ecoles/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/superadmin/ecoles/{id}/modifier', name: 'superadmin_app_ecole_modifier')]
    public function modifier(Request $request, Schools $ecole, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SchoolType::class, $ecole);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('superadmin_app_ecole');
        }

        return $this->render('superAdmin/ecoles/modifier.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/superadmin/ecoles/{id}/supprimer', name: 'superadmin_app_ecole_supprimer')]
    public function supprimer(Request $request, Schools $ecole, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('supprimer' . $ecole->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ecole);
            $entityManager->flush();
        }

        return $this->redirectToRoute('superadmin_app_ecole');
    }
}