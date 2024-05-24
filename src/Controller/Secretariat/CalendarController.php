<?php

namespace App\Controller\Secretariat;

use App\Entity\Courses;
use App\Entity\Evaluations;
use App\Entity\Events;
use App\Form\CoursesType;
use App\Form\EvaluationsType;
use App\Form\EventsType;
use App\Repository\CoursesRepository;
use App\Repository\EvaluationsRepository;
use App\Repository\EventsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CalendarController extends AbstractController
{
    function getDescription($event)
    {
        $students = $event->getStudents();
        $description = '';

        if ($event->getStudentClasses()) {
            foreach ($event->getStudentClasses() as $studentClasses) {
                $description .= $studentClasses->getName();
            }
        } else {
            foreach ($students as $student) {
                $description .= $student->getFirstname() . ', ';
            }
            $description = rtrim($description, ', '); // Supprimer la virgule finale
        }

        $description .= ' - Professeur: ' . $event->getTeacher()->getUserIdentifier();

        return $description;
    }

    #[Route('/secretariat/agenda', name: 'app_secretariat_calendar_home')]
    public function index(EventsRepository $eventsRepository, CoursesRepository $coursesRepository, EvaluationsRepository $evaluationsRepository): Response
    {
        // Récupérer l'utilisateur connecté (vous pouvez adapter cela selon votre logique d'authentification)
        $user = $this->getUser();
        $ecole = $user->getSchool();

        // Récupérer les événements associés à l'utilisateur
        $courses  = $coursesRepository->findBy(['school' => $ecole]);
        $events = $eventsRepository->findBy(['school' => $ecole]);
        $evaluations = $evaluationsRepository->findBy(['school' => $ecole]);
        // Combinez les données récupérées dans un seul tableau $allEvents
        $allEvents = array_merge($courses, $events, $evaluations);

        // Convertir les événements en un format compatible avec FullCalendar (par exemple, JSON)
        $formattedEvents = [];
        foreach ($allEvents as $event) {
            $formattedEvents[] = [
                'title' => $event->getName(),
                'id' => $event->getId(),
                'uniqid' => $event->getIdUnique(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'teacher' => $event->getName(),
                // Ajoutez ici d'autres champs que vous souhaitez afficher dans le calendrier
                'textColor'=>$event->getTextColor(),
                'backgroundColor'=>$event->getBackgroundColor(),
                'borderColor'=>$event->getBackgroundColor(),
            ];
        }

        // Retourner les événements au format JSON
        $jsonEvents = json_encode($formattedEvents);

        return $this->render('secretariat/calendar/index.html.twig', [
            'jsonEvents' => $jsonEvents,
        ]);
    }

    #[Route('/secretariat/agenda/cours/details-evenements', name: 'app_secretariat_calendar_courses_details_event', methods:"POST")]
    public function getEventDetails(Request $request, CoursesRepository $coursesRepository, EventsRepository $eventsRepository, EvaluationsRepository $evaluationsRepository): Response
    {
        $eventId = $request->request->get('eventId');

        // Récupérer les informations de l'événement en fonction de son ID
        $event = $coursesRepository->findOneBy(['id_unique' => $eventId]);
        if ($event) {
            $studentClasses = $event->getStudentClasses();
            $students = $event->getStudents();
            $lecons = $event->getLessons();
            $programmes = $event->getPrograms();
            if ($event->getStart() > new \DateTime & $event->getObjectives() == null) {
                $objectif = "Aucun objectif défini pour le prochain cours";
                $commentaire = null;
            } elseif ($event->getStart() > new \DateTime) {
                $objectif = $event->getObjectives();
                $commentaire = null;
            } elseif ($event->getStart() < new \DateTime &  $event->getComment() == null) {
                $commentaire = "Aucun commentaire défini pour le prochain cours";
                $objectif = null;
            } elseif ($event->getStart() < new \DateTime) {
                $commentaire = $event->getComment();
                $objectif = null;
            }
            if ($event->isRecurrence() || $event->getParent() !== null) {
                $recurrence = 'recurrence';
            } else {
                $recurrence = null;
            }
            // Convertir l'objet Event en tableau associatif
            $eventData = [
                'type' => 'cours',
                'title' => $event->getName(),
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'room' => $event->getRoom() ? $event->getRoom()->getName() : null,
                'teacher' => $event->getTeacher()->getUserIdentifier(),
                'studentClass' => $studentClasses->isEmpty() ? [] : $studentClasses->map(fn ($studentClass) => $studentClass->getName())->toArray(),
                'students' => $students->isEmpty() ? [] : $students->map(fn ($student) => $student->getUserIdentifier())->toArray(),
                'zoomlink' => $event->getZoomLink() ?? '',
                'lecons' => $lecons->isEmpty() ? [] : $lecons->map(fn ($lecons) => [
                    'nom' => $lecons->getName(),
                    'slug' => $lecons->getSlug()
                ])->toArray(),
                'programmes' => $programmes->isEmpty() ? [] : $programmes->map(fn ($programme) => [
                    'nom' => $programme->getName(),
                    'slug' => $programme->getSlug()
                ])->toArray(),
                'objectif' => $objectif,
                'commentaire' => $commentaire,
                'recurrence' => $recurrence,
                // Ajoutez ici d'autres propriétés de l'événement que vous souhaitez inclure dans la réponse JSON
            ];    
        } else {
            $event = $eventsRepository->findOneBy(['id_unique' => $eventId]);
            if ($event) {
            $users = $event->getUsers();
            $programmes = $event->getDescription();
            // Convertir l'objet Event en tableau associatif
            $eventData = [
                'type' => 'event',
                'title' => $event->getName(),
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'users' => $users->isEmpty() ? [] : $users->map(fn ($user) => $user->getUserIdentifier())->toArray(),
                // Ajoutez ici d'autres propriétés de l'événement que vous souhaitez inclure dans la réponse JSON
            ];
            } else {
                $event = $evaluationsRepository->findOneBy(['id_unique' => $eventId]);
                if ($event) {
                    $studentClasses = $event->getStudentClasses();
                    $students = $event->getStudents();
                    $lecons = $event->getLesson();
                    $programmes = $event->getProgram();
                    if ($event->getStart() > new \DateTime & $event->getObjective() == null) {
                        $objectif = "Aucun objectif défini pour le prochain cours";
                        $commentaire = null;
                    } elseif ($event->getStart() > new \DateTime) {
                        $objectif = $event->getObjective();
                        $commentaire = null;
                    } elseif ($event->getStart() < new \DateTime &  $event->getComment() == null) {
                        $commentaire = "Aucun commentaire défini pour le prochain cours";
                        $objectif = null;
                    } elseif ($event->getStart() < new \DateTime) {
                        $commentaire = $event->getComment();
                        $objectif = null;
                    }
                    // Convertir l'objet Event en tableau associatif
                    $eventData = [
                        'type' => 'evaluation',
                        'title' => $event->getName(),
                        'id' => $event->getId(),
                        'start' => $event->getStart()->format('Y-m-d H:i:s'),
                        'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                        'room' => $event->getRoom() ? $event->getRoom()->getName() : null,
                        'teacher' => $event->getTeacher()->getUserIdentifier(),
                        'studentClass' => $studentClasses->isEmpty() ? [] : $studentClasses->map(fn ($studentClass) => $studentClass->getName())->toArray(),
                        'students' => $students->isEmpty() ? [] : $students->map(fn ($student) => $student->getUserIdentifier())->toArray(),
                        'lecons' => $lecons->isEmpty() ? [] : $lecons->map(fn ($lecons) => [
                            'nom' => $lecons->getName(),
                            'slug' => $lecons->getSlug()
                        ])->toArray(),
                        'programmes' => $programmes->isEmpty() ? [] : $programmes->map(fn ($programme) => [
                            'nom' => $programme->getName(),
                            'slug' => $programme->getSlug()
                        ])->toArray(),
                        'objectif' => $objectif,
                        'commentaire' => $commentaire,
                        // Ajoutez ici d'autres propriétés de l'événement que vous souhaitez inclure dans la réponse JSON
                    ];
                } else {
                    return new JsonResponse(['error' => 'L\'événement n\'a pas été trouvé.'], 404);
                }
            }

        }

        // Renvoyer les informations de l'événement en tant que réponse JSON
        return new JsonResponse(['event' => $eventData]);
    }

    #[Route('/secretariat/agenda/cours/creer', name: 'app_secretariat_calendar_courses_add')]
    public function coursesadd(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Courses();
        $user = $this->getUser();
        $isAdminRoute = true;
        $ecole =  $user->getSchool();

        $form = $this->createForm(CoursesType::class, $event, ['ecole' => $ecole, 'isAdminRoute' => $isAdminRoute, 'user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Calculer l'heure de fin en fonction de l'heure de début et de la durée
            $start = $form->get('start')->getData(); // Récupérer l'objet DateTime depuis le formulaire
            $event->setStart($start);

            $title = $form->get('discipline')->getData() . ' - ';
            $studentClassData = $form->get('studentClasses')->getData();
            $studentsData = $form->get('students')->getData();
            $numberOfStudentsClasses = count($studentClassData);
            $numberOfStudents = count($studentsData);
            $last = 1;
            $event->setSchool($ecole);

            if ($numberOfStudentsClasses !==0) {
                foreach ($studentClassData as $studentClass) {
                    $title .= $studentClass->getName();
                    if (!$last === $numberOfStudentsClasses) {
                        $title .= ", ";
                    }
                    $last++;
                }
            } elseif ($numberOfStudents > 0) {
                $title .= $studentsData[0];
                if ($numberOfStudents > 1) {
                    $title .= ', ';
                    if ($numberOfStudents > 2) {
                        $title .=  $studentsData[0] . ', ...';
                    } else {
                        $title .=  $studentsData[0];
                    }
                }
            }
            $event->setName($title);
            $event->setTextColor('#FFFFFF');
            $event->setBackgroundColor("#BECA31");
            $duration = $event->getDuration();
            $event->setIdUnique(uniqid(mt_rand(), true));
            $end = clone $start;
            $end->modify("+$duration minutes");
            $event->setEnd($end);
            // Enregistrer l'événement dans la base de données
            $entityManager->persist($event);
            $entityManager->flush();
            // Récupérer les données de récurrence depuis le formulaire
            $recurrence = $form->get('recurrence')->getData();
            // Si la récurrence est activée
        
            if ($recurrence === true ) {
                $recurrenceEnd = $form->get('recurrence_end')->getData();
                $recurrenceFrequency = $form->get('recurrence_frequency')->getData();
                if ($recurrenceEnd) {
                    // Créez les événements récurrents
                    $currentDate = clone $start;
                    $endDate = clone $end;
                    while ($currentDate <= $recurrenceEnd) {
                        $recurringEvent = $this->createRecurringEvent($form, $currentDate, $endDate, $duration, $event);
                        $entityManager->persist($recurringEvent);
                        // Passez à la prochaine occurrence en fonction de la fréquence
                        if ($recurrenceFrequency === 'daily') {
                            $currentDate->modify("+1 day");
                            $endDate->modify("+1 day");
                        } elseif ($recurrenceFrequency === 'weekly') {
                            $currentDate->modify("+1 week");
                            $endDate->modify("+1 week");
                        } elseif ($recurrenceFrequency === 'monthly') {
                            $currentDate->modify("+1 month");
                            $endDate->modify("+1 month");
                        }
                        $entityManager->flush();
                    }
                }
            } else {
                $event->setRecurrenceEnd(null);
                $entityManager->persist($event);
                $entityManager->flush();
            }
            $this->addFlash('success', 'L\'événement a été créé avec succès.');
            return $this->redirectToRoute('app_secretariat_calendar_home');  
        }

        return $this->render('secretariat/calendar/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function createRecurringEvent(FormInterface $form, \DateTime $start, \DateTime $end, int $duration, Courses $parentEvent): Courses
    {
        $recurringEvent = new Courses();
        
        $ecole =  $this->getUser()->getSchool();
    
        $recurringEvent->setName($parentEvent->getName());
        $recurringEvent->setDiscipline($form->get('discipline')->getData());
        $studentClasses = $form->get('studentClasses')->getData();
        foreach ($studentClasses as $studentClass) {
            $recurringEvent->addStudentClass($studentClass);
        }
        $students = $form->get('students')->getData();
        $recurringEvent->setSchool($ecole);
        foreach ($students as $student) {
            $recurringEvent->addStudent($student);
        }
        $recurringEvent->setTeacher($form->get('teacher')->getData());
    
        $recurringEvent->setStart($start);
        $recurringEvent->setRecurrence(false);
        $recurringEvent->setRoom($form->get('room')->getData());
        $recurringEvent->setEnd($end);
        $recurringEvent->setDuration($duration);
        $recurringEvent->setIdUnique(uniqid(mt_rand(), true));
        $recurringEvent->setParent($parentEvent);
        $recurringEvent->setTextColor('#FFFFFF');
        $recurringEvent->setBackgroundColor("#BECA31");
    
    
        return $recurringEvent;
    }

    #[Route('/secretariat/agenda/cours/{id}/supprimer/', name: 'app_secretariat_calendar_courses_delete')]
    public function coursesdelete(Courses $courses, EntityManagerInterface $entityManager): Response
    {
        if ($courses->isRecurrence() == 1) {
            $getEventsRecurrence = $entityManager->getRepository(Courses::class)
                ->createQueryBuilder('e')
                ->where('e.parent = :parentEvent')
                ->andWhere('e.id > :eventId') // Modifier cette condition pour obtenir l'événement suivant
                ->setParameter('parentEvent', $courses)
                ->setParameter('eventId', $courses->getId())
                ->orderBy('e.id', 'ASC') // Triez par ID pour obtenir l'événement suivant
                ->getQuery()
                ->getResult();
        
            $newParent = null;
        
            if (!empty($getEventsRecurrence)) {
                // L'événement suivant dans la liste sera le nouvel événement parent
                $newParent = $getEventsRecurrence[0];
        
                // Transférer les propriétés de l'événement actuel au nouvel événement parent
                $newParent->setRecurrence($courses->isRecurrence());
                $newParent->setRecurrenceEnd($courses->getRecurrenceEnd());
                $newParent->setRecurrenceFrequency($courses->getRecurrenceFrequency());
                $newParent->setParent(null);
                
                foreach ($getEventsRecurrence as $getEventRecurrence) {
                    if ($getEventRecurrence !== $courses && $getEventRecurrence !== $newParent) {
                        // Mettre à jour le parent de l'événement de récurrence
                        $getEventRecurrence->setParent($newParent);
                        $entityManager->persist($getEventRecurrence);
                    }
                }
            }
        }

        // Supprimer l'événement de la base de données
        $entityManager->remove($courses);
        $entityManager->flush();
        $this->addFlash('success', 'L\'événement a été supprimé avec succès.');

        return $this->redirectToRoute('app_secretariat_calendar_home');
    }

    #[Route('/secretariat/agenda/cours/{id}/supprimer/tous-les-cours', name: 'app_secretariat_calendar_courses_delete_all_courses')]
    public function deleteAllCourses(Courses $courses, EntityManagerInterface $entityManager): Response
    {
        // Initialiser un tableau d'identifiants d'événements à modifier
        $eventIdsToDelete = [];   
        // Vérifiez si l'événement a une récurrence ou un parentEventId
        if ($courses->isRecurrence() == 1) {
            // Récupérez tous les événements ayant le même parentEvent
            $eventsToDelete = $entityManager->getRepository(Courses::class)->createQueryBuilder('e')
                ->where('e.parent = :parentEvent')
                ->andWhere('e.id != :eventId') // Excluez l'événement actuel
                ->setParameter('parentEvent', $courses)
                ->setParameter('eventId', $courses->getId())
                ->getQuery()
                ->getResult();
            
            foreach ($eventsToDelete as $eventToDelete) {
                $eventIdsToDelete[] = $eventToDelete->getId();
            }
        } elseif ($courses->getParent() !== null) {
            // Récupérez tous les événements ayant le même parentEvent ou l'ID du parentEvent
            $eventsToDelete = $entityManager->getRepository(Courses::class)->createQueryBuilder('e')
                ->where('e.parent = :parentEvent OR (e.id = :parentId AND e.id != :eventId)') // Excluez l'événement actuel
                ->setParameter('parentEvent', $courses->getParent())
                ->setParameter('parentId', $courses->getParent()->getId())
                ->setParameter('eventId', $courses->getId())
                ->getQuery()
                ->getResult();
            
            foreach ($eventsToDelete as $eventToDelete) {
                $eventIdsToDelete[] = $eventToDelete->getId();
            }
        }

        foreach ($eventsToDelete as $eventToDelete) {
            if ($eventToDelete !== $courses) {
                
            // Supprimer l'événement de la base de données
            $entityManager->remove($eventToDelete);
            $entityManager->flush();
            }
        }

        // Supprimer l'événement de la base de données
        $entityManager->remove($courses);
        $entityManager->flush();

        $this->addFlash('success', 'L\'événement a été supprimé avec succès.');

        return $this->redirectToRoute('app_secretariat_calendar_home');
    }

    #[Route('/secretariat/agenda/cours/{id}/supprimer/cours-futur', name: 'app_secretariat_calendar_courses_delete_future_courses')]
    public function deleteFuturCourses(Courses $courses, EntityManagerInterface $entityManager): Response
    {
        // Initialiser un tableau d'identifiants d'événements à modifier
        $eventIdsToDelete = [];

        // Vérifiez si l'événement a une récurrence ou un parentEventId
        if ($courses->isRecurrence() == 1) {
            // Récupérez tous les événements ayant le même parentEvent
            $eventsToDelete = $entityManager->getRepository(Courses::class)->createQueryBuilder('e')
                ->where('e.parent = :parentEvent')
                ->andWhere('e.id != :eventId') // Excluez l'événement actuel
                ->setParameter('parentEvent', $courses)
                ->setParameter('eventId', $courses->getId())
                ->getQuery()
                ->getResult();

            // Filtrer les événements futurs
            $startEvent = $courses->getStart();
            $eventsToDelete = array_filter($eventsToDelete, function ($eventToDelete) use ($startEvent) {
                return $eventToDelete->getStart() > $startEvent;
            });

            foreach ($eventsToDelete as $eventToDelete) {
                $eventIdsToDelete[] = $eventToDelete->getId();
            }
        } elseif ($courses->getParent() !== null) {
            // Récupérez tous les événements ayant le même parentEvent ou l'ID du parentEvent
            $eventsToDelete = $entityManager->getRepository(Courses::class)->createQueryBuilder('e')
                ->where('e.parent = :parentEvent OR (e.id = :parentId AND e.id != :eventId)') // Excluez l'événement actuel
                ->setParameter('parentEvent', $courses->getParent())
                ->setParameter('parentId', $courses->getParent()->getId())
                ->setParameter('eventId', $courses->getId())
                ->getQuery()
                ->getResult();

            // Filtrer les événements futurs
            $startEvent = $courses->getStart();
            $eventsToDelete = array_filter($eventsToDelete, function ($eventToDelete) use ($startEvent) {
                return $eventToDelete->getStart() > $startEvent;
            });

            foreach ($eventsToDelete as $eventToDelete) {
                $eventIdsToDelete[] = $eventToDelete->getId();
            }
        }

        foreach ($eventsToDelete as $eventToDelete) {
            if ($eventToDelete !== $courses) {
                
            // Supprimer l'événement de la base de données
            $entityManager->remove($eventToDelete);
            $entityManager->flush();
            }
        }
        // Supprimer l'événement de la base de données
        $entityManager->remove($courses);
        $entityManager->flush();

        $this->addFlash('success', 'L\'événement a été supprimé avec succès.');

        return $this->redirectToRoute('app_secretariat_calendar_home');
    }

    #[Route('/secretariat/agenda/cours/{id}/modifier', name: 'app_secretariat_calendar_courses_modify')]
    public function coursesmodify(Courses $courses,Request $request, EntityManagerInterface $entityManager): Response
    {
        // Sauvegardez l'heure de début d'origine
        $originalStart = $courses->getStart();
        $user = $this->getUser();
        $isAdminRoute = true;
        $ecole =  $user->getSchool();

        $form = $this->createForm(CoursesType::class, $courses, ['ecole' => $ecole, 'isAdminRoute' => $isAdminRoute, 'user' => $user]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $start = $form->get('start')->getData();
            $courses->setStart($start);

            $title = $form->get('discipline')->getData() . ' - ';
            $studentClassData = $form->get('studentClasses')->getData();
            $studentsData = $form->get('students')->getData();
            $numberOfStudentsClasses = count($studentClassData);
            $numberOfStudents = count($studentsData);
            $last = 1;
            

            if ($studentClassData) {
                foreach ($studentClassData as $studentClass) {
                    $title .= $studentClass->getName();
                    if (!$last === $numberOfStudentsClasses) {
                        $title .= ", ";
                    }
                    $last++;
                }
            } elseif ($numberOfStudents > 0) {
                $title .= $studentsData[0];

                if ($numberOfStudents > 1) {
                    $title .= ', ';

                    if ($numberOfStudents > 2) {
                        $title .= $studentsData[1] . ', ...';
                    } else {
                        $title .= $studentsData[1];
                    }
                }
            }
            $courses->setName($title);

            $duration = $courses->getDuration();

            $end = clone $start;
            $end->modify("+$duration minutes");
            $courses->setEnd($end);

            // Récupérez le champ `modificationScope` de la soumission du formulaire
            $modificationScope = null;
            if ($form->has('modificationScope')) {
                $modificationScope = $form->get('modificationScope')->getData();
            }

            // Selon la portée de la modification, appliquez la logique appropriée
            if ($modificationScope !== null) {
                switch ($modificationScope) {
                    case 'this_event':
                        // Ne rien faire de plus, car l'événement actuel est déjà modifié
                    break;
                    case 'all_events':
                        // Initialiser un tableau d'identifiants d'événements à modifier
                        $eventIdsToModify = [];   
                        // Vérifiez si l'événement a une récurrence ou un parentEventId
                        if ($courses->isRecurrence() == 1) {
                            // Récupérez tous les événements ayant le même parentEvent
                            $eventsToModify = $entityManager->getRepository(Courses::class)->createQueryBuilder('e')
                                ->where('e.parent = :parentEvent')
                                ->andWhere('e.id != :eventId') // Excluez l'événement actuel
                                ->setParameter('parentEvent', $courses)
                                ->setParameter('eventId', $courses->getId())
                                ->getQuery()
                                ->getResult();
                            
                            foreach ($eventsToModify as $eventToModify) {
                                $eventIdsToModify[] = $eventToModify->getId();
                            }
                        } elseif ($courses->getParent() !== null) {
                            // Récupérez tous les événements ayant le même parentEvent ou l'ID du parentEvent
                            $eventsToModify = $entityManager->getRepository(Courses::class)->createQueryBuilder('e')
                                ->where('e.parent = :parentEvent OR (e.id = :parentId AND e.id != :eventId)') // Excluez l'événement actuel
                                ->setParameter('parentEvent', $courses->getParent())
                                ->setParameter('parentId', $courses->getParent()->getId())
                                ->setParameter('eventId', $courses->getId())
                                ->getQuery()
                                ->getResult();
                            
                            foreach ($eventsToModify as $eventToModify) {
                                $eventIdsToModify[] = $eventToModify->getId();
                            }
                        }

                        
                        // Obtenez la différence entre l'heure de début d'origine et la nouvelle heure de début
                        $startDifference = $originalStart->diff($courses->getStart());
                        // Récupérez les événements à modifier en utilisant les identifiants
                        $eventsToModify = $entityManager->getRepository(Courses::class)->findBy(['id' => $eventIdsToModify]);
                        // Modifiez tous ces événements
                        foreach ($eventsToModify as $eventToModify) {
                            if ($eventToModify !== $courses) {
                                // Appliquez les modifications nécessaires
                                $newStart = clone $eventToModify->getStart();

                                // Extrait l'heure et les minutes de $event->getStart()
                                $startTime = $courses->getStart()->format('H:i');
                                // Extrait l'heure de $startTime
                                list($hours, $minutes) = explode(':', $startTime);
                                
                                // Ajoutez ou soustrayez la différence en minutes à l'heure de début de l'événement
                                $newStart->add($startDifference);
                                $newStart->setTime($hours, $minutes);

                                $eventToModify->setStart($newStart);

                                // Vous pouvez ajouter d'autres modifications ici si nécessaire
                                $eventToModify->setRoom($form->get('room')->getData());
                                $eventToModify->setTeacher($form->get('teacher')->getData());
                                $eventToModify->setDiscipline($form->get('discipline')->getData());
                                $eventToModify->setDuration($form->get('duration')->getData());
                                // Code pour gérer la modification des relations de programme
                                $newProgrammes = $form->get('programs')->getData();
                                $currentProgrammes = $eventToModify->getPrograms();
                                
                                // Supprimez les programmes qui ne sont plus sélectionnés
                                foreach ($currentProgrammes as $programme) {
                                    if (!$newProgrammes->contains($programme)) {
                                        $eventToModify->removePrograms($programme);
                                    }
                                }
                                
                                // Ajoutez les nouveaux programmes
                                foreach ($newProgrammes as $programme) {
                                    if (!$currentProgrammes->contains($programme)) {
                                        $eventToModify->addProgram($programme);
                                    }
                                }
                                // Code pour gérer la modification des relations "lecons"
                                $newLecons = $form->get('lessons')->getData();
                                $currentLecons = $eventToModify->getLessons();

                                // Supprimez les leçons qui ne sont plus sélectionnées
                                foreach ($currentLecons as $lecon) {
                                    if (!$newLecons->contains($lecon)) {
                                        $eventToModify->removeLessons($lecon);
                                    }
                                }

                                // Ajoutez les nouvelles leçons
                                foreach ($newLecons as $lecon) {
                                    if (!$currentLecons->contains($lecon)) {
                                        $eventToModify->addLesson($lecon);
                                    }
                                }

                                // Code pour gérer la modification des relations "students"
                                $newStudentClasses = $form->get('studentClasses')->getData();
                                $currentstudentClasses = $eventToModify->getStudents();
                                $newStudents = $form->get('students')->getData();
                                $currentStudents = $eventToModify->getStudents();

                                // Supprimez les étudiants qui ne sont plus sélectionnés
                                foreach ($currentStudents as $student) {
                                    if (!$newStudents->contains($student)) {
                                        $eventToModify->removeStudent($student);
                                    }
                                }
                                // Ajoutez les nouveaux étudiants
                                foreach ($newStudents as $student) {
                                    if (!$currentStudents->contains($student)) {
                                        $eventToModify->addStudent($student);
                                    }
                                }
                                // Mettez à jour la classe d'étudiant
                                // Supprimez les étudiants qui ne sont plus sélectionnés
                                foreach ($currentstudentClasses as $studentclasses) {
                                    if (!$newStudentClasses->contains($studentclasses)) {
                                        $eventToModify->removeStudentClasses($studentclasses);
                                    }
                                }
                                // Ajoutez les nouveaux étudiants
                                foreach ($newStudentClasses as $studentclasses) {
                                    if (!$currentstudentClasses->contains($studentclasses)) {
                                        $eventToModify->addStudentClass($studentclasses);
                                    }
                                }

                                $title = $form->get('discipline')->getData() . ' - ';
                                $studentClassData = $form->get('studentClasses')->getData();
                                $studentsData = $form->get('students')->getData();
                                $numberOfStudentsClasses = count($studentClassData);
                                $numberOfStudents = count($studentsData);
                                $last = 1;
                                

                                if ($studentClassData) {
                                    foreach ($studentClassData as $studentClass) {
                                        $title .= $studentClass->getName();
                                        if (!$last === $numberOfStudentsClasses) {
                                            $title .= ", ";
                                        }
                                        $last++;
                                    }
                                } elseif ($numberOfStudents > 0) {
                                    $title .= $studentsData[0];

                                    if ($numberOfStudents > 1) {
                                        $title .= ', ';

                                        if ($numberOfStudents > 2) {
                                            $title .= $studentsData[1] . ', ...';
                                        } else {
                                            $title .= $studentsData[1];
                                        }
                                    }
                                }
                                $eventToModify->setName($title);
                                

                                $duration = $courses->getDuration();

                                $end = clone $newStart;
                                $end->modify("+$duration minutes");
                                $eventToModify->setEnd($end);

                                // Enregistrez l'événement modifié dans la base de données
                                $entityManager->persist($eventToModify);
                            } 
                        }
                    break;
                        
                    case 'future_events':
                        // Initialiser un tableau d'identifiants d'événements à modifier
                        $eventIdsToModify = [];

                        // Vérifiez si l'événement a une récurrence ou un parentEventId
                        if ($courses->isRecurrence() == 1) {
                            // Récupérez tous les événements ayant le même parentEvent
                            $eventsToModify = $entityManager->getRepository(Courses::class)->createQueryBuilder('e')
                                ->where('e.parent = :parentEvent')
                                ->andWhere('e.id != :eventId') // Excluez l'événement actuel
                                ->setParameter('parentEvent', $courses)
                                ->setParameter('eventId', $courses->getId())
                                ->getQuery()
                                ->getResult();

                            // Filtrer les événements futurs
                            $startEvent = $courses->getStart();
                            $eventsToModify = array_filter($eventsToModify, function ($eventToModify) use ($startEvent) {
                                return $eventToModify->getStart() > $startEvent;
                            });

                            foreach ($eventsToModify as $eventToModify) {
                                $eventIdsToModify[] = $eventToModify->getId();
                            }
                        } elseif ($courses->getParent() !== null) {
                            // Récupérez tous les événements ayant le même parentEvent ou l'ID du parentEvent
                            $eventsToModify = $entityManager->getRepository(Courses::class)->createQueryBuilder('e')
                                ->where('e.parent = :parentEvent OR (e.id = :parentId AND e.id != :eventId)') // Excluez l'événement actuel
                                ->setParameter('parentEvent', $courses->getParent())
                                ->setParameter('parentId', $courses->getParent()->getId())
                                ->setParameter('eventId', $courses->getId())
                                ->getQuery()
                                ->getResult();

                            // Filtrer les événements futurs
                            $startEvent = $courses->getStart();
                            $eventsToModify = array_filter($eventsToModify, function ($eventToModify) use ($startEvent) {
                                return $eventToModify->getStart() > $startEvent;
                            });

                            foreach ($eventsToModify as $eventToModify) {
                                $eventIdsToModify[] = $eventToModify->getId();
                            }
                        }

                        // Obtenez la différence entre l'heure de début d'origine et la nouvelle heure de début
                        $startDifference = $originalStart->diff($courses->getStart());
                        // Récupérez les événements à modifier en utilisant les identifiants
                        $eventsToModify = $entityManager->getRepository(Courses::class)->findBy(['id' => $eventIdsToModify]);
                        // Modifiez tous ces événements
                        foreach ($eventsToModify as $eventToModify) {
                            if ($eventToModify !== $courses) {
                                // Appliquez les modifications nécessaires
                                $newStart = clone $eventToModify->getStart();

                                // Extrait l'heure et les minutes de $event->getStart()
                                $startTime = $courses->getStart()->format('H:i');
                                // Extrait l'heure de $startTime
                                list($hours, $minutes) = explode(':', $startTime);
                                
                                // Ajoutez ou soustrayez la différence en minutes à l'heure de début de l'événement
                                $newStart->add($startDifference);
                                $newStart->setTime($hours, $minutes);

                                $eventToModify->setStart($newStart);

                                // Vous pouvez ajouter d'autres modifications ici si nécessaire
                                $eventToModify->setRoom($form->get('room')->getData());
                                $eventToModify->setTeacher($form->get('teacher')->getData());
                                $eventToModify->setDiscipline($form->get('discipline')->getData());
                                $eventToModify->setDuration($form->get('duration')->getData());
                                $eventToModify->setZoomLink($form->get('zoomlink')->getData());
                                // Code pour gérer la modification des relations de programme
                                $newProgrammes = $form->get('programs')->getData();
                                $currentProgrammes = $eventToModify->getPrograms();

                                // Supprimez les programmes qui ne sont plus sélectionnés
                                foreach ($currentProgrammes as $programme) {
                                    if (!$newProgrammes->contains($programme)) {
                                        $eventToModify->removePrograms($programme);
                                    }
                                }

                                // Ajoutez les nouveaux programmes
                                foreach ($newProgrammes as $programme) {
                                    if (!$currentProgrammes->contains($programme)) {
                                        $eventToModify->addPrograms($programme);
                                    }
                                }
                                // Code pour gérer la modification des relations "lecons"
                                $newLecons = $form->get('lessons')->getData();
                                $currentLecons = $eventToModify->getLessons();

                                // Supprimez les leçons qui ne sont plus sélectionnées
                                foreach ($currentLecons as $lecon) {
                                    if (!$newLecons->contains($lecon)) {
                                        $eventToModify->removeLessons($lecon);
                                    }
                                }

                                // Ajoutez les nouvelles leçons
                                foreach ($newLecons as $lecon) {
                                    if (!$currentLecons->contains($lecon)) {
                                        $eventToModify->addLesson($lecon);
                                    }
                                }

                                // Code pour gérer la modification des relations "students"
                                $studentClass = $form->get('studentClasses')->getData();
                                $newStudents = $form->get('students')->getData();
                                $currentStudents = $eventToModify->getStudents();

                                // Supprimez les étudiants qui ne sont plus sélectionnés
                                foreach ($currentStudents as $student) {
                                    if (!$newStudents->contains($student)) {
                                        $eventToModify->removeStudent($student);
                                    }
                                }

                                // Ajoutez les nouveaux étudiants
                                foreach ($newStudents as $student) {
                                    if (!$currentStudents->contains($student)) {
                                        $eventToModify->addStudent($student);
                                    }
                                }

                                // Mettez à jour la classe d'étudiant
                                $eventToModify->setStudentClasses($studentClass);

                                $title = $form->get('discipline')->getData() . ' - ';
                                $studentClassData = $form->get('studentClasses')->getData();
                                $studentsData = $form->get('students')->getData();
                                $numberOfStudentsClasses = count($studentClassData);
                                $numberOfStudents = count($studentsData);
                                $last = 1;
                                

                                if ($studentClassData) {
                                    foreach ($studentClassData as $studentClass) {
                                        $title .= $studentClass->getName();
                                        if (!$last === $numberOfStudentsClasses) {
                                            $title .= ", ";
                                        }
                                        $last++;
                                    }
                                } elseif ($numberOfStudents > 0) {
                                    $title .= $studentsData[0];

                                    if ($numberOfStudents > 1) {
                                        $title .= ', ';

                                        if ($numberOfStudents > 2) {
                                            $title .= $studentsData[1] . ', ...';
                                        } else {
                                            $title .= $studentsData[1];
                                        }
                                    }
                                }
                                $eventToModify->setTitle($title);


                                $duration = $courses->getDuration();

                                $end = clone $newStart;
                                $end->modify("+$duration minutes");
                                $eventToModify->setEnd($end);

                                // Enregistrez l'événement modifié dans la base de données
                                $entityManager->persist($eventToModify);
                            } 
                        }
                    break;
                    default:
                        // Par défaut, ne rien faire
                    break;
                }
            }

            // Enregistrez l'événement actuel dans la base de données
            $entityManager->persist($courses);
            $entityManager->flush();

            $this->addFlash('success', 'L\'événement a été modifié avec succès.');

            return $this->redirectToRoute('app_secretariat_calendar_courses_details', ['id' => $courses->getId()]);
        }

        return $this->render('secretariat/calendar/form.html.twig', [
            'form' => $form->createView(),
            'event' => $courses,
        ]);
    }

    #[Route('/secretariat/agenda/cours/{id}', name: 'app_secretariat_calendar_courses_details')]
    public function coursesdetails(Courses $courses): Response
    {
        if ($courses->isRecurrence() == 1 || $courses->getParent() !== null) {
            $recurrence = 'recurrence';
        } else {
            $recurrence = null;
        }

        return $this->render('secretariat/calendar/show.html.twig', [
            'event' => $courses,
            'recurrence' => $recurrence,
        ]);
    }

    #[Route('/api/secretariat/agenda/{uniqid}/export/telecharger', name: 'app_secretariat_calendar_download')]
    public function download(CoursesRepository $coursesRepository, EvaluationsRepository $evaluationsRepository): Response
    {
        // Récupérer l'utilisateur connecté (vous pouvez adapter cela selon votre logique d'authentification)
        $user = $this->getUser();

        // Récupérer les événements associés à l'utilisateur
        $courses = $coursesRepository->findEventsByUser($user);
        $events = $user->getEvents()->toArray();
        $evaluations = $evaluationsRepository->findEventsByUser($user);

        $allEvents = [];

        foreach ($courses as $course) {
            $allEvents[] = ['event' => $course, 'type' => 'course'];
        }

        foreach ($events as $event) {
            $allEvents[] = ['event' => $event, 'type' => 'event'];
        }

        foreach ($evaluations as $evaluation) {
            $allEvents[] = ['event' => $evaluation, 'type' => 'evaluation'];
        }

        dump($allEvents);

        // Calculer la date du premier et dernier événement
        $firstEventItem = reset($allEvents);
        $lastEventItem = end($allEvents);
        $firstEvent = $firstEventItem['event'];
        $lastEvent = $lastEventItem['event'];
        $startDate = $firstEvent->getStart()->format('Ymd');
        $endDate = $lastEvent->getEnd()->format('Ymd');
        $userDateOfBirth = $user->getDateOfBirth()->format('Y-m-d');

        // Générer le contenu du fichier iCalendar
        $content = "BEGIN:VCALENDAR\r\n";
        $content .= "VERSION:2.0\r\n";
        $content .= "PRODID;LANGUAGE=fr:Copyright ATempo-Education @" . date('Y') . "\r\n";
        $content .= "X-CALSTART:" . $startDate . "\r\n";
        $content .= "X-CALEND:" . $endDate . "\r\n";
        $content .= "X-WR-CALNAME;LANGUAGE=fr:ATempo - " . $user->getLastname() . " " . $user->getFirstname() . " - " . $userDateOfBirth . " - " . "\r\n";
        $content .= "X-WR-CALDESC;LANGUAGE=fr:Emploi du temps " . $user->getLastname() . " " . $user->getFirstname() . " - " . $userDateOfBirth . " - " . " | Généré par ATempo.Education - Semaines :" . date('W') . " - " . $lastEvent->getEnd()->format('W') . "\r\n";

        foreach ($allEvents as $item) {
            $event = $item['event'];
            $type = $item['type'];
            dump($event);

            $content .= "BEGIN:VEVENT\r\n";
            $content .= "CATEGORIES:ATempo.Education\r\n";
            $content .= "UID:" . $event->getIdUnique() . " " . $user . "\r\n";
            $content .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
            $content .= "DTSTART:" . $event->getStart()->format('Ymd\THis\Z') . "\r\n";
            $content .= "DTEND:" . $event->getEnd()->format('Ymd\THis\Z') . "\r\n";
            if ($event->getRoom() != null) {
                $content .= "LOCATION:" . $event->getRoom()->getName() . "\r\n";
            } elseif ($event->getZoomLink() != null) {
                $content .= "LOCATION: Lien Zoom : " . $event->getZoomLink() . "\r\n";
            }
            
            $description = "";

            if ($type == 'event' && $event->getUsers()->count() > 0) {
                $description .= "Participants: ";
                foreach ($event->getUsers() as $user) {
                    $description .= $user->getName() . ", ";
                }
                // Supprimer la virgule finale
                $description = rtrim($description, ", ") . "\\n";
            } elseif ($event->getStudentClasses()) {
                foreach ($event->getStudentClasses() as $studentClass) {
                    $description .= "Classe: " . $studentClass->getName() . "\\n";
                }
            } elseif ($event->getStudents()->count() > 0) {
                $description .= "Élèves: ";
                foreach ($event->getStudents() as $student) {
                    $description .= $student->getName() . ", ";
                }
                // Supprimer la virgule finale
                $description = rtrim($description, ", ") . "\\n";
            }

            if ($type != "event" && $event->getTeacher()) {
                $description .= "Professeur: " . $event->getTeacher() . "\\n";
            }
            
            if ($event->getRoom() != null) {
                $description .= "Salle: " . $event->getRoom()->getName();
            } elseif ($event->getZoomLink() != null) {
                $description .= "Lien Zoom: " . $event->getZoomLink();
            }

            $content .= "SUMMARY:" . $event->getName() . "\r\n";
            $content .= "DESCRIPTION:" . $description . "\r\n";
            $content .= "END:VEVENT\r\n";
        }

        $content .= "END:VCALENDAR";

        // Générer le nom du fichier
        $filename = 'edt_' . $user . '.ics';

        // Envoi du fichier iCalendar en tant que réponse
        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/calendar');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    #[Route('/secretariat/agenda/examen/creer', name: 'app_secretariat_calendar_evaluation_add')]
    public function evaluationadd(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Evaluations();
        $user = $this->getUser();
        $isAdminRoute = true;
        $ecole =  $user->getSchool();
        

        $form = $this->createForm(EvaluationsType::class, $event, ['ecole' => $ecole, 'isAdminRoute' => $isAdminRoute, 'user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Calculer l'heure de fin en fonction de l'heure de début et de la durée
            $start = $form->get('start')->getData(); // Récupérer l'objet DateTime depuis le formulaire
            $event->setStart($start);

            $title = 'Examen ' . $form->get('discipline')->getData() . ' - ';
            $studentClassData = $form->get('studentClasses')->getData();
            $studentsData = $form->get('students')->getData();
            $numberOfStudentsClasses = count($studentClassData);
            $numberOfStudents = count($studentsData);
            $last = 1;
            $event->setSchool($ecole);

            if ($numberOfStudentsClasses !==0) {
                foreach ($studentClassData as $studentClass) {
                    $title .= $studentClass->getName();
                    if (!$last === $numberOfStudentsClasses) {
                        $title .= ", ";
                    }
                    $last++;
                }
            } elseif ($numberOfStudents > 0) {
                $title .= $studentsData[0];
                if ($numberOfStudents > 1) {
                    $title .= ', ';
                    if ($numberOfStudents > 2) {
                        $title .=  $studentsData[0] . ', ...';
                    } else {
                        $title .=  $studentsData[0];
                    }
                }
            }
            $event->setName($title);
            $event->setTextColor('#FFFFFF');
            $event->setBackgroundColor("#439492");
            $duration = $event->getDuration();
            $event->setIdUnique(uniqid(mt_rand(), true));
            $end = clone $start;
            $end->modify("+$duration minutes");
            $event->setEnd($end);
            // Enregistrer l'événement dans la base de données
            $entityManager->persist($event);
            $entityManager->flush();
        
            
            $this->addFlash('success', 'L\'événement a été créé avec succès.');
            return $this->redirectToRoute('app_secretariat_calendar_home');  
        }

        return $this->render('secretariat/evaluation/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secretariat/agenda/examen/{id}/supprimer', name: 'app_secretariat_calendar_evaluation_delete')]
    public function evaluationdelete(Evaluations $evaluations, EntityManagerInterface $entityManager): Response
    {

        // Supprimer l'événement de la base de données
        $entityManager->remove($evaluations);
        $entityManager->flush();
        $this->addFlash('success', 'L\'éxamen a été supprimé avec succès.');

        return $this->redirectToRoute('app_secretariat_calendar_home');
    }

    #[Route('/secretariat/agenda/examen/{id}/modifier', name: 'app_secretariat_calendar_evaluation_modify')]
    public function evaluationmodify(Evaluations $evaluations, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $isAdminRoute = true;
        $ecole =  $user->getSchool();
        

        $form = $this->createForm(EvaluationsType::class, $evaluations, ['ecole' => $ecole, 'isAdminRoute' => $isAdminRoute, 'user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Calculer l'heure de fin en fonction de l'heure de début et de la durée
            $start = $form->get('start')->getData(); // Récupérer l'objet DateTime depuis le formulaire
            $evaluations->setStart($start);

            $title = 'Examen ' . $form->get('discipline')->getData() . ' - ';
            $studentClassData = $form->get('studentClasses')->getData();
            $studentsData = $form->get('students')->getData();
            $numberOfStudentsClasses = count($studentClassData);
            $numberOfStudents = count($studentsData);
            $last = 1;
            $evaluations->setSchool($ecole);

            if ($numberOfStudentsClasses !==0) {
                foreach ($studentClassData as $studentClass) {
                    $title .= $studentClass->getName();
                    if (!$last === $numberOfStudentsClasses) {
                        $title .= ", ";
                    }
                    $last++;
                }
            } elseif ($numberOfStudents > 0) {
                $title .= $studentsData[0];
                if ($numberOfStudents > 1) {
                    $title .= ', ';
                    if ($numberOfStudents > 2) {
                        $title .=  $studentsData[0] . ', ...';
                    } else {
                        $title .=  $studentsData[0];
                    }
                }
            }
            $evaluations->setName($title);
            $evaluations->setTextColor('#FFFFFF');
            $evaluations->setBackgroundColor("#439492");
            $duration = $evaluations->getDuration();
            $evaluations->setIdUnique(uniqid(mt_rand(), true));
            $end = clone $start;
            $end->modify("+$duration minutes");
            $evaluations->setEnd($end);
            // Enregistrer l'événement dans la base de données
            $entityManager->persist($evaluations);
            $entityManager->flush();
        
            
            $this->addFlash('success', 'L\'éxamen a été modifié avec succès.');
            return $this->redirectToRoute('app_secretariat_calendar_home');  
        }

        return $this->render('secretariat/evaluation/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secretariat/agenda/examen/{id}', name: 'app_secretariat_calendar_evaluation_details')]
    public function evaluationsdetails(Evaluations $evaluations): Response
    {

        return $this->render('secretariat/evaluation/show.html.twig', [
            'event' => $evaluations,
        ]);
    }

    #[Route('/secretariat/agenda/evenement/creer', name: 'app_secretariat_calendar_event_add')]
    public function eventadd(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Events();
        $ecole =  $this->getUser()->getSchool();
        

        $form = $this->createForm(EventsType::class, $event, ['ecole' => $ecole]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Calculer l'heure de fin en fonction de l'heure de début et de la durée
            $start = $form->get('start')->getData(); // Récupérer l'objet DateTime depuis le formulaire
            $event->setStart($start);
            $event->setSchool($ecole);
            $event->setIdUnique(uniqid(mt_rand(), true));
            $event->setTextColor('#FFFFFF');
            $event->setBackgroundColor("#E9A48B");
            $end = $form->get('end')->getData(); // Récupérer l'objet DateTime depuis le formulaire
            $event->setEnd($end);
            // Enregistrer l'événement dans la base de données
            $entityManager->persist($event);
            $entityManager->flush();
        
            
            $this->addFlash('success', 'L\'événement a été créé avec succès.');
            return $this->redirectToRoute('app_secretariat_calendar_home');  
        }

        return $this->render('secretariat/event/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secretariat/agenda/evenement/{id}/supprimer', name: 'app_secretariat_calendar_event_delete')]
    public function eventdelete(Events $event, EntityManagerInterface $entityManager): Response
    {
        // Supprimer l'événement de la base de données
        $entityManager->remove($event);
        $entityManager->flush();
        $this->addFlash('success', 'L\'évenement a été supprimé avec succès.');

        return $this->redirectToRoute('app_secretariat_calendar_home');
    }

    #[Route('/secretariat/agenda/evenement/{id}/modifier', name: 'app_secretariat_calendar_event_modify')]
    public function eventmodify(Events $event, EntityManagerInterface $entityManager, Request $request): Response
    {
        $ecole =  $this->getUser()->getSchool();
        
        $form = $this->createForm(EventsType::class, $event, ['ecole' => $ecole]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Calculer l'heure de fin en fonction de l'heure de début et de la durée
            $start = $form->get('start')->getData(); // Récupérer l'objet DateTime depuis le formulaire
            $event->setStart($start);

            $event->setSchool($ecole);

            
            $event->setTextColor('#FFFFFF');
            $event->setBackgroundColor("#E9A48B");
            $end = $form->get('end')->getData(); // Récupérer l'objet DateTime depuis le formulaire
            $event->setEnd($end);
            // Enregistrer l'événement dans la base de données
            $entityManager->persist($event);
            $entityManager->flush();
        
            
            $this->addFlash('success', 'L\'évenement a été modifié avec succès.');
            return $this->redirectToRoute('app_secretariat_calendar_home');  
        }

        return $this->render('secretariat/event/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secretariat/agenda/evenement/{id}', name: 'app_secretariat_calendar_event_details')]
    public function eventdetails(Events $events): Response
    {

        return $this->render('secretariat/event/show.html.twig', [
            'event' => $events,
        ]);
    }
}
