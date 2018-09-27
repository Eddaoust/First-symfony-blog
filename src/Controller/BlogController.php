<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesAddType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="blog")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
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
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('show', [
                'id' => $article->getId()
            ]);
        }
        return $this->render('blog/add_article.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @param $id
     * @return Response
     *
     * @Route("/show/{id}", name="show", requirements={"page"="\d+"})
     */
    public function showArticle($id)
    {
        $article = $this->getDoctrine()
            ->getRepository(Articles::class)
            ->find($id);

        if (!$article)
        {
            throw $this->createNotFoundException('Pas d\article!');
        }

        return new Response(
            '<h1>'.$article->getTitle().'</h1>
                    <p>'.$article->getContent().'</p>'
        );
    }

}
