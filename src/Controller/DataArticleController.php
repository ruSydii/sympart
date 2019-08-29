<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DataArticleController extends AbstractController
{
    /**
     * @Route("/article", name="data_article")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('data_article/index.html.twig',array('articles'=>$articles));
    }

    /**
     * @Route("/article/show/{id}" ,name="artikel_show")
     * @Method({"GET"})
     */
    public function show($id)
    {
        $articles= $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('data_article/show.html.twig',array('articles'=>$articles));
    }

    /**
     * @Route("/article/create" , name="new_artcle")
     * @Method({"GET"}, {"POST"})
     *
     */
    public function new(Request $request)
    {
        $article=  new Article();

        $form = $this->createFormBuilder($article)
        ->add('article',TextType::class ,array('attr'=>array('class' => 'form-control')))
        ->add('deskripsi',TextareaType::class,array('required' => false,'attr'=>array('class'=> 'form-control')))
            ->add('save',SubmitType::class,array('label'=>'Create','attr'=>array('class'=>'btn btn-primary mt-3')))
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('data_article');
        }

        return $this->render('/data_article/new.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @Route("/article/edit/{id}" , name="edit_artcle")
     * @Method({"GET"}, {"POST"})
     *
     */
    public function edit(Request $request , $id)
    {
        $article=  new Article();
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
            ->add('article',TextType::class ,array('attr'=>array('class' => 'form-control')))
            ->add('deskripsi',TextareaType::class,array('required' => false,'attr'=>array('class'=> 'form-control')))
            ->add('save',SubmitType::class,array('label'=>'Update','attr'=>array('class'=>'btn btn-primary mt-3')))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('data_article');
        }

        return $this->render('/data_article/edit.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @Route("/article/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        $this->addFlash('succes', 'data telah dihapus');

    }


//    /**
//     * @Route("/article/save" , name="save_product")
//     */
//
//    public function save()
//    {
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $article = new Article();
//        $article->setArticle('Berita siang');
//        $article->setDeskripsi("pada hari minngu kuturut ayah kekota");
//
//        $entityManager->persist($article);
//        $entityManager->flush();
//
//        return new Response('save artikel with id'.$article->getId());
//    }
}
