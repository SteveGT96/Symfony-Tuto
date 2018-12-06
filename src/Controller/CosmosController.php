<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Composer\Repository\ArtifactRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use \Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class CosmosController extends AbstractController
{
    /**
     * @Route("/cosmos", name="cosmos")
     */
    public function index(ArticleRepository $repo)
    {
        $articles=$repo->findAll();
        return $this->render('cosmos/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home() {
        return $this->render("cosmos/home.html.twig", ['nom'=>"Tsala", 'pnom'=>"Steve"]);
    }
    /**
     * @Route("/cosmos/new", name="create")
     * @Route("/cosmos/{id}/edit", name="edit")
     */
    public function form(Article $article = null, Request $request, ObjectManager $manager) {
        if(!$article) {
            $article = new Article();
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('show', ['id'=>$article->getId()]);
        }
        return $this->render('cosmos/create.html.twig', [
            'formArticle'=>$form->createView(),
            'editMode'=>$article->getId()!=null
        ]);
    }
    /**
     * @Route("/cosmos/{id}", name="show" )
     */
    public function show($id, Article $article) {
        return $this->render('cosmos/show.html.twig', ['article' => $article]);
    }
}
