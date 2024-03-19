<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use App\Repository\CategoriesRepository;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostsController extends AbstractController
{
    #[Route('/actualites', name: 'app_posts_home')]
    public function index(PostsRepository $postsRepository, CategoriesRepository $categoriesRepository, Request $request): Response
    {
        $ecole = $this->getUser()->getSchool();
        $articles = $postsRepository->findBy(['is_active' =>true, 'school' => $ecole], ['id'=>'DESC']);

        // On définit le nombre d'éléments par page
        $limit = 8;

        // On récupère le numéro de page
        $page = (int)$request->query->get("page", 1);

        // On récupère les filtres
        $filters = $request->get("categories");

        // On récupère les annonces de la page en fonction du filtre
        $articles = $postsRepository->getPaginatedArticles($page, $limit, $filters);

        // On récupère le nombre total d'articles
        $totalarticles = $postsRepository->getTotalArticles($filters);


        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('actualites/_content.html.twig', ['articles' => $articles, 'totalarticles' => $totalarticles, 'limit' => $limit, 'page' => $page])
            ]);
        }
        // On va chercher toutes les catégories
        $categories = $categoriesRepository->findBy(['school' => $ecole]);

        return $this->render('posts/index.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'totalarticles' =>$totalarticles,
            'limit'=>$limit,
            'page' =>$page,
        ]);
    }

    #[Route('/actualités/creer', name: 'app_posts_add')]
    public function add(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $article = new Posts;
        $ecole = $this->getUser()->getSchool();
        $form = $this->createForm(PostsType::class, $article, ['ecole' => $ecole]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());
            $article->setisActive(true);
            $article->setSlug($slugger->slug($form->get('name')->getData()));
            $article->setSchool($ecole);

            $images = $form->get('thumbnail')->getData();

            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                try {
                    $images->move(
                        $this->getParameter('posts_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $article->setThumbnail($newFilename);
            }else{
                $article->setThumbnail('default_img.jpg');
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_home');
        }
        

        return $this->render('posts/form.html.twig', [
            'form' => $form->createView(),
            'articles' => $article,
        ]);
    }

    #[Route('/actualités/{slug}/modifier', name: 'app_posts_modify')]
    public function modify(Posts $article, SluggerInterface $slugger, Request $request, EntityManagerInterface $entityManager): Response
    {
        $ecole = $this->getUser()->getSchool();
        $form = $this->createForm(PostsType::class, $article, ['ecole' => $ecole]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());
            $article->setisActive(true);
            $article->setSlug($slugger->slug($form->get('name')->getData()));
            $article->setSchool($ecole);

            $images = $form->get('thumbnail')->getData();

            if ($images) {
                $originalFilename = pathinfo($images->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $images->guessExtension();

                try {
                    $images->move(
                        $this->getParameter('posts_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }
                $article->setThumbnail($newFilename);
            }else{
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_posts_details', ['slug' => $article->getSlug()]);
        }
        

        return $this->render('posts/form.html.twig', [
            'form' => $form->createView(),
            'post' => $article,
        ]);

        return $this->render('posts/form.html.twig', [
        ]);
    }

    #[Route('/actualités/{slug}', name: 'app_posts_details')]
    public function details(PostsRepository $postsRepository, $slug, Request $request): Response
    {
        $article = $postsRepository->findOneBy(['slug' => $slug]);
        if(!$article instanceof \App\Entity\Posts){
            throw new NotFoundHttpException('L\'article n\'a pas été trouvé');
        }

        return $this->render('posts/details.html.twig', [
            'article' => $article,
        ]);
    }
}
