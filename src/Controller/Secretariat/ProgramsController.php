<?php

namespace App\Controller\Secretariat;

use App\Entity\Programs;
use App\Form\ProgramsType;
use App\Repository\LessonsRepository;
use App\Repository\ProgramsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProgramsController extends AbstractController
{
    #[Route('/secretariat/programmes', name: 'app_secretariat_programs_home')]
    public function index(ProgramsRepository $programsRepository): Response
    {
        $ecole =  $this->getUser()->getSchool();
        $allprogrammes = $programsRepository->findBy(["school" => $ecole]);

        return $this->render('secretariat/programs/index.html.twig', [
            'programmes'=>$allprogrammes,
        ]);
    }

    #[Route('/secretariat/programmes/ajouter', name: 'app_secretariat_programs_add')]
    public function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $programs = new Programs();
        $ecole =  $this->getUser()->getSchool();
        $form = $this->createForm(ProgramsType::class, $programs, [
            'ecole' => $ecole,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programs->addUser($this->getUser());
            $programs->setSchool($ecole);
            $images = $form->get('thumbnail')->getData();
            $programs->setSlug($slugger->slug($form->get('name')->getData()));
            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                try {
                    $images->move(
                        $this->getParameter('programs_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $programs->setThumbnail($newFilename);
            }else{
                $programs->setThumbnail('default_img.jpg');
            }
            
            $entityManager->persist($programs);
            $entityManager->flush();

            return $this->redirectToRoute('app_secretariat_programs_home');
        }

        return $this->render('secretariat/programs/form.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/secretariat/programmes/{slug}/modifier', name: 'app_secretariat_programs_modify')]
    public function modify(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager, Programs $programs): Response
    {
        $ecole =  $this->getUser()->getSchool();
        $form = $this->createForm(ProgramsType::class, $programs, [
            'ecole' => $ecole,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programs->addUser($this->getUser());
            $programs->setSchool($ecole);
            $images = $form->get('thumbnail')->getData();
            $programs->setSlug($slugger->slug($form->get('name')->getData()));
            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                try {
                    $images->move(
                        $this->getParameter('programs_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $programs->setThumbnail($newFilename);
            }
            
            $entityManager->persist($programs);
            $entityManager->flush();

            dump($programs->getSlug());

            return $this->redirectToRoute('app_secretariat_programs_home');
        }

        return $this->render('secretariat/programs/form.html.twig', [
            'form'=>$form->createView(),
            'program'=>$programs
        ]);
    }

    #[Route('/secretariat/programmes/supprimer/{id}', name: 'app_secretariat_programs_delete')]
    public function delete(Programs $programs, EntityManagerInterface $entityManager, LessonsRepository $lessonsRepository): Response
    {
        $getLecons = $lessonsRepository->findBy(['program'=>$programs]);
        if($getLecons !== [] ){
            foreach($getLecons as $lecon){
                $programs->removeLesson($lecon);
            }
        }

        $entityManager->remove($programs);
        $entityManager->flush();

        return $this->redirectToRoute('app_secretariat_programs_home');
    }

    #[Route('/secretariat/programmes/{slug}', name: 'app_secretariat_programs_details')]
    public function details(ProgramsRepository $programsRepository, $slug): Response
    {
        $programs = $programsRepository->findOneBy(['slug' => $slug]);
        if(!$programs instanceof \App\Entity\programs){
            throw new NotFoundHttpException('Le programme n\'a pas été trouvé');
        };

        return $this->render('secretariat/programs/details.html.twig', [
            'programme' => $programs
        ]);
    }
}
