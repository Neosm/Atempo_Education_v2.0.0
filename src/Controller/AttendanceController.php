<?php

namespace App\Controller;

use App\Entity\Absences;
use App\Entity\Courses;
use App\Entity\Delays;
use App\Entity\Evaluations;
use App\Form\AttendanceType;
use App\Repository\CoursesRepository;
use App\Repository\EvaluationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class AttendanceController extends AbstractController
{
    #[Route('/assiduite', name: 'app_attendance_home')]
    public function index(): Response
    {
        return $this->render('attendance/index.html.twig', [
        ]);
    }
    #[Route('/assiduite/ajouter/{type}/{id}', name: 'app_attendance_add')]
    public function addAttendance(Request $request, EntityManagerInterface $entityManager, CoursesRepository $coursesRepository, EvaluationsRepository $evaluationsRepository, string $type, int $id, SessionInterface $session): Response
    {
        $event = $this->getEventByIdAndType($coursesRepository, $evaluationsRepository, $type, $id);
        $students = $this->getStudentsFromEvent($event);

        // Créer les collections retards et absences
        $retards = [];
        $absences = [];
        foreach ($students as $student) {
            $retards[] = ['student' => $student];
            $absences[] = ['student' => $student];
        }

        // Passez les collections initiales au formulaire
        $form = $this->createForm(AttendanceType::class, [
            'retards' => $retards,
            'absences' => $absences,
        ], [
            'students' => $students,
            'course' => $type === 'courses' ? $event : null,
            'evaluation' => $type === 'evaluations' ? $event : null,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            foreach ($formData['retards'] as $retardData) {
                if ($retardData['delay_time'] !== null) {
                    $retard = new Delays();
                    // Convertir l'entier en DateTime
                    $delayTime = new \DateTime();
                    $delayTime->setTime(0, $retardData['delay_time']);
                    $retard->setDelayTime($delayTime);
                    $retard->setStudent($retardData['student']);
                    $retard->setSchool($this->getUser()->getSchool());
                    
                    // Définir correctement course ou evaluation
                    if ($type === 'courses') {
                        $retard->setCourse($event);
                    } elseif ($type === 'evaluations') {
                        $retard->setEvaluation($event);
                    }

                    $entityManager->persist($retard);
                }
            }

            foreach ($formData['absences'] as $absenceData) {
                if ($absenceData['est_absent']) {
                    $absence = new Absences();
                    $absence->setStudent($absenceData['student']);
                    $absence->setSchool($this->getUser()->getSchool());
                    
                    // Définir correctement course ou evaluation
                    if ($type === 'courses') {
                        $absence->setCourse($event);
                    } elseif ($type === 'evaluations') {
                        $absence->setEvaluation($event);
                    }

                    $entityManager->persist($absence);
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_attendance_home');
        }

        return $this->render('attendance/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function getEventByIdAndType(CoursesRepository $coursesRepository, EvaluationsRepository $evaluationsRepository, string $type, int $id)
    {
        if ($type === 'courses') {
            return $coursesRepository->find($id);
        } elseif ($type === 'evaluations') {
            return $evaluationsRepository->find($id);
        }

        throw new \InvalidArgumentException('Type d\'événement non valide.');
    }

    private function getStudentsFromEvent($event)
    {
        $students = [];

        if ($event instanceof Courses) {
            foreach ($event->getStudents() as $student) {
                $students[] = $student;
            }
            foreach ($event->getStudentClasses() as $studentClass) {
                foreach ($studentClass->getStudents() as $student) {
                    $students[] = $student;
                }
            }
        } elseif ($event instanceof Evaluations) {
            foreach ($event->getStudents() as $student) {
                $students[] = $student;
            }
            foreach ($event->getStudentClasses() as $studentClass) {
                foreach ($studentClass->getStudents() as $student) {
                    $students[] = $student;
                }
            }
        } else {
            throw new \InvalidArgumentException('Type d\'événement non valide.');
        }

        // Forcer l'initialisation des collections
        foreach ($students as $student) {
            if ($student instanceof PersistentCollection) {
                $student->initialize();
            }
        }

        return $students;
    }
}