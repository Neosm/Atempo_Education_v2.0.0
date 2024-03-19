<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UsersController extends AbstractController
{
    #[Route('/users', name: 'app_users_home')]
    public function index(UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();

        // Récupérer tous les événements liés à l'utilisateur ou à la studentClass
        $events = $user->getCoursesteacher()->toArray();
        $userData = $usersRepository->findByrolesStudent($events);

        return $this->render('users/index.html.twig', [
            'userStudent' => $userData,
        ]);
        
    }
}
