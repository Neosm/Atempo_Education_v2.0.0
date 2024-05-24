<?php

namespace App\Controller;

use App\Form\DefinedNewPasswordType;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class AccountController extends AbstractController
{
    #[Route('/mon-profil', name: 'app_account_home')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/mon-profil/mot-de-passe', name: 'app_account_password')]
    public function NewPassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $notification_miss = null;
        $notification_good = null;
        $user = $this->getUser();
        $form = $this->createForm(DefinedNewPasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $old_pwd = $form->get('old_password')->getData();
            if ($passwordHasher->isPasswordValid($user, $old_pwd)){
                $new_pwd = $form->get('new_password')->getData();
                $password = $passwordHasher->hashPassword($user, $new_pwd);

                $user->setPassword($password);

                $entityManager->flush();

                $notification_good = "Votre mot de passe à bien était mis à jour.";
            }else{
                $notification_miss = "Votre mot de passe actuel n'est pas valide";
            }
        }

        return $this->render('account/password.html.twig',[
            'form'=> $form->CreateView(),
            'notification_good'=>$notification_good,
            'notification_miss'=>$notification_miss
        ]);
    }

    #[Route('/mon-profil/informations', name: 'app_account_informations')]
    public function informations(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);
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
                    $user->setThumbnail($newFilename);
                }
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('message_sucess', "Vos informations ont pu être mis à jour.");
            }else {
                $this->addFlash('message_alert',"Vos informations n'ont pas pu être mis à jour");
            }
            return $this->redirectToRoute('app_account_home');
        }

        return $this->render('account/information.html.twig',[
            'form'=> $form->CreateView(),
        ]);
    }
}
