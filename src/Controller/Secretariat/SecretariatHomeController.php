<?php

namespace App\Controller\Secretariat;

use App\Repository\CoursesRepository;
use App\Repository\EvaluationsRepository;
use App\Repository\EventsRepository;
use App\Repository\PostsRepository;
use App\Repository\StudentClassesRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecretariatHomeController extends AbstractController
{
    #[Route('/secretariat/', name: 'app_secretariat_home')]
    public function index(EventsRepository $eventsRepository, 
    PostsRepository $artsRepo, CoursesRepository $coursesRepository, 
    EvaluationsRepository $evaluationsRepository, StudentClassesRepository $studentClassesRepository): Response
    {
        // Récupérer l'utilisateur connecté (vous pouvez adapter cela selon votre logique d'authentification)
        $user = $this->getUser();
        $ecole = $user->getSchool();
        $studentclasses  = $studentClassesRepository->findBy(['school' => $ecole]);

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

        $articles = $artsRepo->findBy(['is_active' =>true, 'school' => $ecole]);


        return $this->render('secretariat/home/index.html.twig', [
            'jsonEvents' => $jsonEvents,
            'articles' => $articles,
            'classes' => $studentclasses,
        ]);
    }
}
