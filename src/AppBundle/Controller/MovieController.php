<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Review;

use AppBundle\Form\ReviewType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class MovieController extends Controller
{


    public function findAllFilmsAction($page)
    {
        $nextpage= $page+1;
        $previouspage = $page-1;


        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movies = $repo->findBy([], ["year" => "DESC"], 50,($page- 1)* 50);

        $repo=$this->getDoctrine()->getRepository("AppBundle:Movie");
        $count = $repo-> countAll();


        return $this->render('AppBundle:Default:index.html.twig', ["movies" => $movies, "page"=>$page, "nextpage"=>$nextpage,
        "previouspage"=>$previouspage, "count"=> $count]);
    }


    public function detailAction($id, Request $request)
    {

        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie = $repo->find($id);

        if ($movie === null) {
            throw new NotFoundHttpException("Page not found");
        }

        $review = new Review();
        $review->setMovie($movie);

        $form=$this->createForm(ReviewType::class,$review);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            $this->addFlash("success", "votre critique est ajouté");
        }
        return $this->render('AppBundle:Movie:detail.html.twig', [
        "movie" => $movie,"reviewForm"=> $form->createView()

        ]);

    }




}