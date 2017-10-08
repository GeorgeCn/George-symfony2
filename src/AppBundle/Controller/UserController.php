<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Entity\Books;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
 
        $qb = $em->getRepository('AppBundle:User')->createQueryBuilder('u');
 
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($qb, $request->query->getInt('page', 1));
        
        return $this->render('user/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    public function newAction(Request $request)
    {
        $data = 'Welcome';
        
        return $this->render('user/new.html.twig',['data'=>$data]);
    }

    /**
     * @Route("/demo", name="user_demo")
     * @Method("GET")
     */
    public function demoAction()
    {
        $data = 'Welcome';
        
        return $this->render('user/new.html.twig',['data'=>$data]);
    }
}
