<?php

namespace App\Controller\Secretariat;

use App\Entity\Lessons;
use App\Entity\LessonsPdf;
use App\Form\LessonsType;
use App\Repository\LessonsAudiosRepository;
use App\Repository\LessonsPdfRepository;
use App\Repository\LessonsRepository;
use App\Repository\LessonsVideosRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class LessonsController extends AbstractController
{
    #[Route('/secretariat/lecons', name: 'app_secretariat_lessons_home')]
    public function index(LessonsRepository $lessonsRepository): Response
    {
        $ecole =  $this->getUser()->getSchool();
        $allLessons = $lessonsRepository->findBy(['school' => $ecole]);

        return $this->render('secretariat/lessons/index.html.twig', [
            'lecons' => $allLessons
        ]);
    }

    #[Route('/secretariat/lecons/ajouter', name: 'app_secretariat_lessons_add')]
    public function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $lessons = new lessons();
        $ecole =  $this->getUser()->getSchool();
        $form = $this->createForm(LessonsType::class, $lessons, [
            'ecole' => $ecole,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lessons->addUser($this->getUser());
            $lessons->setSchool($ecole);
            $images = $form->get('thumnail')->getData();
            $lessons->setSlug($slugger->slug($form->get('name')->getData()));
            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                try {
                    $images->move(
                        $this->getParameter('lessons_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $lessons->setThumnail($newFilename);
            }else{
                $lessons->setThumnail('default_img.jpg');
            }
            $pdfs = $form->get('pdf')->getData();
            if ($pdfs) {
                foreach ($pdfs as $pdf) {
                    $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdf->guessExtension();
                    try {
                        $pdf->move(
                            $this->getParameter('lessons_pdfs_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                    $newPDF = new LessonsPdf();
                    $newPDF->setLink($newFilename);
                    $newPDF->setname($newFilename);
                    $newPDF->setSchool($ecole);

                    $entityManager->persist($newPDF);
                    $entityManager->flush();
                    

                    $lessons->addLessonsPDF($newPDF);
                    $entityManager->persist($lessons);
                    dump($lessons);
                }
                    
            }
            $entityManager->persist($lessons);
            $entityManager->flush();

            return $this->redirectToRoute('app_secretariat_lessons_home');
        }

        return $this->render('secretariat/lessons/form.html.twig', [
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/secretariat/lecons/{slug}/modifier', name: 'app_secretariat_lessons_modify')]
    public function modify(Request $request, Lessons $lessons, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $ecole =  $this->getUser()->getSchool();
        $form = $this->createForm(LessonsType::class, $lessons, [
            'ecole' => $ecole,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lessons->addUser($this->getUser());
            $lessons->setSchool($ecole);
            $images = $form->get('thumnail')->getData();
            $lessons->setSlug($slugger->slug($form->get('name')->getData()));
            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                try {
                    $images->move(
                        $this->getParameter('lessons_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $lessons->setThumnail($newFilename);
            }else{
            }
            $pdfs = $form->get('pdf')->getData();
            if ($pdfs) {
                foreach ($pdfs as $pdf) {
                    $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $pdf->guessExtension();
                    try {
                        $pdf->move(
                            $this->getParameter('lessons_pdfs_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    }
                    $newPDF = new LessonsPdf();
                    $newPDF->setLink($newFilename);
                    $newPDF->setname($newFilename);
                    $newPDF->setSchool($ecole);

                    $entityManager->persist($newPDF);
                    $entityManager->flush();
                    

                    $lessons->addLessonsPDF($newPDF);
                    $entityManager->persist($lessons);
                }
                    
            }
            $entityManager->persist($lessons);
            $entityManager->flush();

            return $this->redirectToRoute('app_secretariat_lessons_details', ['slug' => $lessons->getSlug()] );
        }

            return $this->render('secretariat/lessons/form.html.twig', [
                'lesson' => $lessons,
                'form' => $form
        ]);
    }

    #[Route('/secretariat/lecons/supprimer/{id}', name: 'app_secretariat_lessons_delete')]
    public function delete(Lessons $lesson, EntityManagerInterface $entityManager, LessonsPdfRepository $lessonsPdfRepository, LessonsVideosRepository $lessonsVideosRepository, LessonsAudiosRepository $lessonsAudiosRepository): Response
    {
        $pdfs = $lessonsPdfRepository->findBy(['lesson' => $lesson]);
        $videos = $lessonsVideosRepository->findBy(['lesson' => $lesson]);
        $audios = $lessonsAudiosRepository->findBy(['lesson' => $lesson]);

        if ($pdfs) {
            foreach ($pdfs as $pdf) {
                $pdfFilePath = $this->getParameter('lessons_pdfs_directory') . '/' . $pdf->getLink();
                if (file_exists($pdfFilePath)) {
                    unlink($pdfFilePath);
                }
                $entityManager->remove($pdf);
            }
        }
        if ($videos) {
            foreach ($videos as $video) {
                $entityManager->remove($video);
            }
        }
        if ($audios) {
            foreach ($audios as $audio) {
                $entityManager->remove($audio);
            }
        }

        $entityManager->remove($lesson);
        $entityManager->flush();

        return $this->redirectToRoute('app_secretariat_lessons_home');
    }

    #[Route('/secretariat/lecons/{slug}', name: 'app_secretariat_lessons_details')]
    public function details(LessonsRepository $lessonsRepository, $slug): Response
    {
        $lesson = $lessonsRepository->findOneBy(['slug' => $slug]);
        if(!$lesson instanceof \App\Entity\Lessons){
            throw new NotFoundHttpException('La leçon n\'a pas été trouvé');
        };


        return $this->render('secretariat/lessons/details.html.twig', [
            'lessons' => $lesson
        ]);
    }

    #[Route('/secretariat/lecons/{slug}/pdf/{name}/download', name: 'app_secretariat_lessons_pdf_download')]
    public function pdfdownload(LessonsRepository $lessonsRepository, $slug, $name): Response
    {
        $lesson = $lessonsRepository->findOneBy(['slug' => $slug]);
        $getPdf = $lesson->getLessonsPdfs();
        foreach ($getPdf as $pdf) {
            if ($pdf->getName() == $name) {
                $response = new BinaryFileResponse('uploads/lessons/pdf/'.$pdf->getName());
                $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $pdf->getName());
                return $response;
            }
        }
        
    }

}
