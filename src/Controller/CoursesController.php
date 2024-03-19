<?php

namespace App\Controller;

use App\Repository\LessonsRepository;
use App\Repository\ProgramsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoursesController extends AbstractController
{
    #[Route('/cours', name: 'app_courses')]
    public function index(LessonsRepository $lessonsRepository, ProgramsRepository $programsRepository): Response
    {
        $ecole =  $this->getUser()->getSchool();
        $allprogrammes = $programsRepository->findBy(["school" => $ecole]);

        $ecole =  $this->getUser()->getSchool();
        $allLessons = $lessonsRepository->findBy(['school' => $ecole]);


        return $this->render('courses/index.html.twig', [
            'lessons' => $allLessons,
            'programs' => $allprogrammes
        ]);
    }
}
