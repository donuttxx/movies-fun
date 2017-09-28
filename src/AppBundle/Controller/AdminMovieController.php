<?php
/**
 * Created by PhpStorm.
 * User: abianco2017
 * Date: 25/09/2017
 * Time: 12:25
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Movie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\MovieType;
use Symfony\Component\HttpFoundation\Request;



class AdminMovieController  extends Controller
{

    public function adminlistAction()
    {

        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movies = $repo->findBy([], ["id" => "ASC"], 50);

        return $this->render('AppBundle:admin:listeModifiable.html.twig', ["movies" => $movies]);

    }

    public function admincreateAction(Request $request)
    {
        $movie = new Movie();

        $movie->setDateCreated(new \Datetime());
        $movie->setDateModified(new \DateTime());

        $form=$this->createForm(MovieType::class,$movie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
         {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();

            $this->addFlash("success", "le film est ajouté");

        }
        return $this->render('AppBundle:Admin:admincreate.html.twig',[
            "movieForm"=> $form->createView()

        ]);


    }

        public function admineditAction($id, Request $request)
        {

            $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
            $movie = $repo->find($id);
            $movie->setDateModified(new \DateTime());

            if ($movie === null) {

                throw $this->createNotFoundException("Pas de Bol");

                //
                //return $this->render('NantesBundle:Event:404.html.twig');
            }
            $form = $this->createForm(MovieType::class, $movie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($movie);
                $em->flush();

                $this->addFlash("success",
                    "Le film a bien été modifié!");

                    return $this->redirectToRoute("movie_detail",
                        ["id" => $movie->getId()]);

                }

                return $this->render('AppBundle:Admin:adminedit.html.twig', [
                    "movieForm" => $form->createView()]);
            }

    public function admindeleteAction($id)
    {

        $repo = $this->getDoctrine()->getRepository("AppBundle:Movie");
        $movie =$repo->find($id);

        if (!$movie){
            throw $this->createNotFoundException("Pas de Bol");
        }

        $em= $this->getDoctrine()->getManager();
        $em->remove($movie);

        $em->flush();

        $this->addFlash("success","le film a été supprimé!");

        return $this->redirectToRoute("movie_admin");


    }


}