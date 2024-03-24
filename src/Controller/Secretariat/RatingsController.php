<?php

namespace App\Controller\Secretariat;

use App\Repository\RatingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RatingsController extends AbstractController
{
    #[Route('/secretariat/notes', name: 'app_secretariat_ratings_home')]
    public function index(RatingsRepository $ratingsRepository): Response
    {
        $ecole = $this->getUser()->getSchool();
        $notes = $ratingsRepository->findBy(["school" => $ecole]);

        return $this->render('secretariat/ratings/index.html.twig', [
            'notes' => $notes
        ]);
    }
}
