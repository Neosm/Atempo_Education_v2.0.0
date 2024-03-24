<?php

namespace App\Controller\Secretariat;

use App\Entity\StudentClasses;
use App\Form\StudentClassesType;
use App\Repository\StudentClassesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StudentClassesController extends AbstractController
{
    #[Route('/secretariat/classe', name: 'app_secretariat_student_classes_home')]
    public function index(StudentClassesRepository $studentClassesRepository): Response
    {
        $ecole = $this->getUser()->getSchool();
        $studentClasses = $studentClassesRepository->findBy(["school" => $ecole]);

        return $this->render('secretariat/student_classes/index.html.twig', [
            'studentClasses' => $studentClasses,
        ]);
    }

    #[Route('/secretariat/classe/{id}', name: 'app_secretariat_student_classes_details')]
    public function details(StudentClasses $studentClass, StudentClassesRepository $studentClassRepository): Response
    {
        $ecole = $this->getUser()->getSchool();
        $studentClasses = $studentClassRepository->findBy(["school" => $ecole]);

        return $this->render('secretariat/student_classes/index.html.twig', [
            'studentClass' => $studentClass,
            'studentClasses' => $studentClasses,
        ]);
    }

    #[Route('/secretariat/creation/classe', name: 'app_secretariat_student_classes_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $studentClasses = new StudentClasses;
        $ecole = $this->getUser()->getSchool();
        $form = $this->createForm(StudentClassesType::class, $studentClasses, ['ecole' => $ecole]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $studentClasses->setSchool($ecole);
            // Récupérer les élèves sélectionnés
            $selectedStudents = $form->get('students')->getData();
            
            // Enregistrer la classe avec les élèves
            $entityManager->persist($studentClasses);
    
            // Ajouter les élèves à la classe
            foreach ($selectedStudents as $student) {
                $student->setStudentClasses($studentClasses);
                $entityManager->persist($student);
            }
            
            $entityManager->flush();
    
            $this->addFlash('success', 'Classe d\'élèves créée avec succès.');
    
            return $this->redirectToRoute('admin_studentclasse_home');
        }
    
        return $this->render('secretariat/student_classes/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secretariat/classe/modifier/{id}', name: 'app_secretariat_student_classes_edit')]
    public function edit(Request $request, StudentClasses $studentClass, EntityManagerInterface $entityManager): Response
    {
        $ecole = $this->getUser()->getSchool();
        // Récupérer les élèves de la classe
        $studentsInClass = $studentClass->getStudents();
    
        // Effectuer une copie profonde des entités d'origine
        $originalStudents = clone $studentsInClass;
    
        // Créer le formulaire et pré-remplir les étudiants de la classe
        $form = $this->createForm(StudentClassesType::class, $studentClass, [
            'studentsInClass' => $studentsInClass,
            'ecole' => $ecole
        ]);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
    
            // Récupérer les élèves sélectionnés
            $selectedStudents = $form->get('students')->getData();

    
            // Supprimer la StudentClass des utilisateurs qui ne sont plus dans la classe
            foreach ($originalStudents as $existingStudent) {
                if (!$selectedStudents->contains($existingStudent)) {
                    $existingStudent->setStudentClasses(null);
                    $entityManager->persist($studentClass);
                }
            }
    
            // Ajouter les élèves à la classe
            foreach ($selectedStudents as $student) {
                $student->setStudentClasses($studentClass);
                $entityManager->persist($student);
            }
            
            $entityManager->flush();

    
            $this->addFlash('success', 'Classe d\'élèves mise à jour avec succès.');
            return $this->redirectToRoute('app_secretariat_student_classes_home');
        }
    
        return $this->render('secretariat/student_classes/form.html.twig', [
            'form' => $form->createView(),
            'studentClass' => $studentClass,
        ]);
    }
    
    
    

    #[Route('/secretariat/classe/supprimer/{id}', name: 'app_secretariat_student_classes_delete')]
    public function delete(StudentClasses $studentClass, EntityManagerInterface $entityManager): Response
    {
        // 1. Retirer les références de la classe d'élèves des utilisateurs et événements liés
        foreach ($studentClass->getStudents() as $user) {
            $user->setStudentClasses(null);
            $entityManager->persist($user);
        }
    
        foreach ($studentClass->getEvaluations() as $event) {
            $event->removeStudentclass($studentClass);
            $entityManager->persist($event);
        }

        foreach ($studentClass->getCourses() as $event) {
            $event->removeStudentclass($studentClass);
            $entityManager->persist($event);
        }
    
        // 2. Supprimer la classe d'élèves
        $entityManager->remove($studentClass);
        $entityManager->flush();
    
        $this->addFlash('success', 'Classe d\'élèves supprimée avec succès.');
    
        return $this->redirectToRoute('app_secretariat_student_classes_home');
    }
}
