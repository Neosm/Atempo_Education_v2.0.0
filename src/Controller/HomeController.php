<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(UsersRepository $usersRepository, PostsRepository $artsRepo): Response
    {
        
        $user = $this->getUser();
        $ecole =  $user->getSchool();
        $articles = $artsRepo->findBy(['is_active' =>true, 'school' => $ecole]);

        //ELEVES / TEACHER LIST
        if ($user->getRoles() == ["ROLE_STUDENT"]){
            // Récupérer la studentClass de l'utilisateur
            $studentClass = $user->getStudentClasses();
            // Récupérer tous les événements liés à l'utilisateur ou à la studentClass
            $events = $user->getCourses()->toArray();
            if ($studentClass) {
                $events = array_merge($events, $studentClass->getEvents()->toArray());
            }
            $userList = $usersRepository->findByrolesTeacher($events);
        } elseif ($user->getRoles() == ["ROLE_TEACHER"]){
            // Récupérer la studentClass de l'utilisateur
            $studentClass = $user->getStudentClasses();
            // Récupérer tous les événements liés à l'utilisateur ou à la studentClass
            $events = $user->getCoursesteacher()->toArray();
            if ($studentClass) {
                $events = array_merge($events, $studentClass->getEvents()->toArray());
            }
            $userList = $usersRepository->findByrolesStudent($events);
        }

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'users' => $userList,
        ]);
    }
}
