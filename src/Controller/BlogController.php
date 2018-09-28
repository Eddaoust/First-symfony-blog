<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesAddType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getManager()
            ->getRepository(Articles::class);
        $articles = $repo->findAll();

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
        $article = $repo->find($id);

        return $this->render('blog/one_article.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/add", name="add_article")
     */
    public function addArticle(Request $request, ObjectManager $manager)
    {
        $article = new Articles();

        $form = $this->createForm(ArticlesAddType::class, $article);
        $form->handleRequest($request);

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
     * @Route("/article_manager", name="article_manager")
     */
    public function updateArticle()
    {
        $repo = $this->getDoctrine()->getManager()
            ->getRepository(Articles::class);
        $articles = $repo->findAll();

        return $this->render('blog/article_manager.html.twig', [
            'articles' => $articles
        ]);
    }
}
