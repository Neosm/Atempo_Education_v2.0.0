<?php

namespace App\Controller\Secretariat;

use App\Entity\Users;
use App\Form\EditProfileType;
use App\Repository\CoursesRepository;
use App\Repository\EvaluationsRepository;
use App\Repository\EventRepository;
use App\Repository\EventsRepository;
use App\Repository\NotesRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class UsersController extends AbstractController
{
    #[Route('/secretariat/utilisateurs', name: 'app_secretariat_users_home')]
    public function index(UsersRepository $usersRepository): Response
    {
        
        $ecole = $this->getUser()->getSchool();
        $getAll = $usersRepository->findBy(["school" => $ecole]);

        return $this->render('secretariat/users/index.html.twig', [
            'users'=>$getAll,
        ]);
    }

    #[Route('/secretariat/eleves', name: 'app_secretariat_users_eleves')]
    public function eleves(UsersRepository $usersRepository): Response
    {
        $ecole = $this->getUser()->getSchool();
        $userData = $usersRepository->findAllStudentByEcole($ecole);

        return $this->render('secretariat/users/index.html.twig', [
            'users' => $userData,
        ]);
    }

    #[Route('/secretariat/professeur', name: 'app_secretariat_users_teachers')]
    public function professeurs(UsersRepository $usersRepository): Response
    {
        $ecole = $this->getUser()->getSchool();
        $userData = $usersRepository->findAllTeacherByEcole($ecole);

        return $this->render('secretariat/users/index.html.twig', [
            'users' => $userData,
        ]);
    }


    #[Route('/secretariat/administration', name: 'app_secretariat_users_administration')]
    public function admin(UsersRepository $usersRepository): Response
    {
        $ecole = $this->getUser()->getSchool();
        $userData = $usersRepository->findAllAdminByEcole($ecole);

        return $this->render('secretariat/users/index.html.twig', [
            'users' => $userData,
        ]);
    }


    #[Route('/secretariat/{id}/voir', name: 'app_secretariat_users_show')]
    public function show(Users $users): Response
    {

        return $this->render('secretariat/users/index.html.twig', [
            'user'=>$users
        ]);
    }



    #[Route('/secretariat/{id}/supprimer', name: 'app_secretariat_users_delete')]
    public function supprimerUser(Request $request, Users $users, EvaluationsRepository $evaluationsRepository, EntityManagerInterface $entityManager, CoursesRepository $coursesRepository): Response
    {
        
        $courses = $coursesRepository->findBy(['teacher' => $users ]);
        $evaluations = $evaluationsRepository->findBy(['teacher' => $users ]);

        
        foreach ($courses as $course) {
            $course->setTeacher(null);
            $entityManager->persist($course);
        }
        foreach ($evaluations as $evaluation) {
            $evaluation->setTeacher(null);
            $entityManager->persist($evaluation);
        }

        // Supprimer l'utilisateur
        $entityManager->remove($users);
        $entityManager->flush();
    
        $this->addFlash('message', 'Utilisateur et notes associées supprimés avec succès.');
        return $this->redirectToRoute('app_secretariat_users_home');
    }


    #[Route('/secretariat/{id}/modifier', name: 'app_secretariat_users_modify')]
    public function modifierUser(Request $request, Users $users, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditProfileType::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if ($form->isValid()) {
                $thumbnail = $form->get('thumbnail')->getData();
                if ($thumbnail) {
                    $originalFilename = pathinfo($thumbnail->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $thumbnail->guessExtension();

                    try {
                        $thumbnail->move(
                            $this->getParameter('thumbnails_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {

                    }
                    $users->setThumbnail($newFilename);
                }
                $entityManager->persist($users);
                $entityManager->flush();
                $this->addFlash('message_sucess', "Les informations de l'utilisateur ont pu être mis à jour.");
            }else {
                $this->addFlash('message_alert',"Les informations de l'utilisateur n'ont pas pu être mis à jour");
            }
            return $this->redirectToRoute('app_secretariat_users_home');
        }

        return $this->render('secretariat/users/informations.html.twig',[
            'form'=> $form->CreateView(),
            'user'=>$users
        ]);
    }

}
