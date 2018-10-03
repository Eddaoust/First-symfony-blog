<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Form\ArticlesAddType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;



class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getManager()
            ->getRepository(Articles::class);
        $articles = $repo->findAll(); // Get de tous les articles pour les afficher

        return $this->render('blog/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @param $id
     * @return Response
     *
     * @Route("/article/{id}", name="one_article")
     */
    public function showArticle($id)
    {
        $repo = $this->getDoctrine()->getManager()
            ->getRepository(Articles::class);
        $article = $repo->find($id); // Recherche d'un article via id

        return $this->render('blog/one_article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/admin/add", name="add_article")
     */
    public function addArticle(Request $request, ObjectManager $manager)
    {
        $article = new Articles(); // Création d'un article vide

        $form = $this->createForm(ArticlesAddType::class, $article); // Création d'un form lié à l'entité Article
        $form->handleRequest($request); // On rempli l'article avec les données du form

        if ($form->isSubmitted() && $form->isValid())
        {
            $article->setCreated(new \DateTime('now'));
            $article->setUser($this->getUser());
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('one_article', [
                'id' => $article->getId()
            ]);
        }
        return $this->render('blog/add_article.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article_manager", name="article_manager")
     */
    public function listArticle()
    {
        $repo = $this->getDoctrine()->getManager()
            ->getRepository(Articles::class);
        $articles = $repo->findAll();

        return $this->render('blog/article_manager.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @param $id
     * @Route("/admin/update_article/{id}", name="update_article")
     */

    public function updateArticle(Request $request, ObjectManager $manager, $id)
    {
        $repo = $this->getDoctrine()->getManager()
            ->getRepository(Articles::class);
        $article = $repo->find($id); // Get de l'article à update

        $form = $this->createForm(ArticlesAddType::class, $article); // Création du Form rempli avec les données de l'entité
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('one_article', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('blog/update_article.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
