<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;


    class UserController extends Controller
    {


    public function registerAction(Request $request)
        {
            $connectedUser = $this->getUser();

            if($connectedUser){

                $this->addFlash('warning', "vous êtes déjà inscrit Gros Guignol");
                return $this->redirectToRoute('movie_list');
            }

            $user = new User();


            $form=$this->createForm(UserType::class,$user);

            $user->setRegesitrationDate(new \DateTime());

            $user->setRoles('ROLE_USER');

            $form->handleRequest($request);




            if ($form ->isSubmitted()&& $form->isValid()){

    $encoder = $this->container->get('security.password_encoder');
    $encoded = $encoder->encodePassword($user,$user->getPassword());
    $user->setPassword($encoded);

    $em = $this->getDoctrine()->getManager();
    $em->persist($user);
    $em->flush();

    $this->addFlash("success",
    "vous faites maintenant parti de notre famille!");
    return $this->redirectToRoute('movie_list');


    }

    return $this->render('AppBundle:User:register.html.twig',[
    "UserForm"=> $form->createView()

    ]);
    }

        public function loginAction(Request $request)
        {
            // get the login error if there is one
            $authUtils = $this->get('security.authentication_utils');
            $error = $authUtils->getLastAuthenticationError();

            // last username entered by the user
            $lastUsername = $authUtils->getLastUsername();

            return $this->render('AppBundle:User:login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]);
        }

    }
