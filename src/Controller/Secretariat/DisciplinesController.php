<?php

namespace App\Controller\Secretariat;

use App\Entity\Courses;
use App\Entity\Disciplines;
use App\Entity\Ratings;
use App\Form\DisciplinesType;
use App\Repository\CoursesRepository;
use App\Repository\DisciplinesRepository;
use App\Repository\EvaluationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DisciplinesController extends AbstractController
{
    #[Route('/secretariat/disciplines', name: 'app_secretariat_disciplines_home')]
    public function index(DisciplinesRepository $disciplinesRepository): Response
    {
        $ecole = $this->getUser()->getSchool();
        $matieres = $disciplinesRepository->findBy(["school" => $ecole]);

        return $this->render('secretariat/disciplines/index.html.twig', [
            'matieres' => $matieres,
        ]);
    }

    #[Route('/secretariat/disciplines/creer', name: 'app_secretariat_disciplines_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ecole =  $this->getUser()->getSchool();
        $matiere = new Disciplines();
        $form = $this->createForm(DisciplinesType::class, $matiere, ['ecole' => $ecole]);
        
        $form->handleRequest($request);
        
        $ecole = $this->getUser()->getSchool();
        if ($form->isSubmitted() && $form->isValid()) {
            $matiere->setSchool($ecole);
            $entityManager->persist($matiere);
            $entityManager->flush();

            $this->addFlash('success', 'Discipline créée avec succès.');
            
            return $this->redirectToRoute('app_secretariat_disciplines_home');
        }

        return $this->render('secretariat/disciplines/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secretariat/disciplines/edit/{id}', name: 'app_secretariat_disciplines_edit')]
    public function edit(Request $request, Disciplines $matiere, EntityManagerInterface $entityManager): Response
    {
        $ecole =  $this->getUser()->getSchool();
        $form = $this->createForm(DisciplinesType::class, $matiere, ['ecole' => $ecole]);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Discipline mise à jour avec succès.');
            
            return $this->redirectToRoute('app_secretariat_disciplines_home');
        }

        return $this->render('secretariat/disciplines/form.html.twig', [
            'form' => $form->createView(),
            'matiere' => $matiere,
        ]);
    }

    #[Route('/secretariat/disciplines/delete/{id}', name: 'app_secretariat_disciplines_delete')]
    public function delete(Disciplines $matiere, EntityManagerInterface $entityManager, CoursesRepository $coursesRepository, EvaluationsRepository $evaluationsRepository): Response
    {

        # Suppression des events associés à la Discipline
        $events = $coursesRepository->findBy(['discipline' => $matiere]);
        foreach ($events as $event) {
            if ($event->getDiscipline() === $matiere) {
                $event->setDiscipline(null);
                $entityManager->persist($event);
            }
        }
        $events = $evaluationsRepository->findBy(['discipline' => $matiere]);
        foreach ($events as $event) {
            if ($event->getDiscipline() === $matiere) {
                $event->setDiscipline(null);
                $entityManager->persist($event);
            }
        }

        # Enfin, supprimez la Discipline elle-même
        $entityManager->remove($matiere);
        $entityManager->flush();


        // Suppression de la Discipline elle-même
        $entityManager->remove($matiere);
        $entityManager->flush();

        $this->addFlash('success', 'Discipline supprimée avec succès.');

        return $this->redirectToRoute('app_secretariat_disciplines_home');
    }
}
