<?php

namespace App\Controller\Secretariat;

use App\Entity\Rooms;
use App\Form\RoomsType;
use App\Repository\CoursesRepository;
use App\Repository\EvaluationsRepository;
use App\Repository\EventsRepository;
use App\Repository\RoomsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RoomsController extends AbstractController
{
    #[Route('/secretariat/salles', name: 'app_secretariat_rooms_home')]
    public function index(RoomsRepository $roomsRepository): Response
    {
        
        $ecole = $this->getUser()->getSchool();
        $rooms = $roomsRepository->findBy(["school" => $ecole]);

        return $this->render('secretariat/rooms/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    #[Route('/secretariat/salles/creer', name: 'app_secretariat_rooms_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rooms = new Rooms();
        $ecole = $this->getUser()->getSchool();
        $form = $this->createForm(RoomsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rooms->setSchool($ecole);
            $entityManager->persist($rooms);
            $entityManager->flush();

            $this->addFlash('success', 'Salle créée avec succès.');

            return $this->redirectToRoute('app_secretariat_rooms_home');
        }

        return $this->render('secretariat/rooms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secretariat/salles/modifier/{id}', name: 'app_secretariat_rooms_edit')]
    public function edit(Request $request, Rooms $rooms, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RoomsType::class, $rooms);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Salle mise à jour avec succès.');

            return $this->redirectToRoute('app_secretariat_rooms_home');
        }

        return $this->render('secretariat/rooms/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/secretariat/salles/supprimer/{id}', name: 'app_secretariat_rooms_delete')]
    public function delete(Rooms $rooms, EntityManagerInterface $entityManager): Response
    {    
        // Récupérer les événements associés à la salle
        $eventsWithRoom = $rooms->getEvents();
    
        // Supprimer la salle de chaque événement associé
        foreach ($eventsWithRoom as $event) {
            $event->setRoom(null);
            $entityManager->persist($event);
        }
    
        // Supprimer la salle elle-même
        $entityManager->remove($rooms);
        $entityManager->flush();
    
        $this->addFlash('success', 'Salle supprimée avec succès.');
    
        return $this->redirectToRoute('app_secretariat_rooms_home');
    }

    #[Route('/secretariat/salles/load-events', name: 'load_room_events')]
    public function loadRoomEvents(Request $request, EventsRepository $eventsRepository, CoursesRepository $coursesRepository, EvaluationsRepository $evaluationsRepository): JsonResponse
    {
        $roomId = $request->query->get('roomId');

        // Récupérer les événements associés à l'utilisateur
        $courses  = $coursesRepository->findBy(['room' => $roomId]);
        $events = $eventsRepository->findBy(['room' => $roomId]);
        $evaluations = $evaluationsRepository->findBy(['room' => $roomId]);
        // Combinez les données récupérées dans un seul tableau $allEvents
        $allEvents = array_merge($courses, $events, $evaluations);

        $serializedEvents = [];
        foreach ($allEvents as $event) {
            // Sérialisez les informations nécessaires de chaque événement
            $serializedEvents[] = [
                'id' => $event->getId(),
                'title' => $event->getName(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                // Ajoutez d'autres informations si nécessaire
            ];
        }

        return new JsonResponse(['events' => $serializedEvents]);
    }
    

    #[Route('/secretariat/salles/details/{id}', name: 'app_secretariat_rooms_home_details')]
    public function details(Rooms $rooms, RoomsRepository $roomRepository): Response
    {
        
        $ecole = $this->getUser()->getSchool();
        $allrooms = $roomRepository->findBy(["school" => $ecole]);

        return $this->render('secretariat/rooms/index.html.twig', [
            'room' => $rooms,
            'rooms' => $allrooms,
        ]);
    }
}
