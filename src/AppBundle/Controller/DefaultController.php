<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Entity\User;
use AppBundle\Entity\Books;

/**
 * @Route("/default")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/index", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/demo/{id}",)
     */
    public function demoAction(Request $request)
    {
        // $a = $this->getRequest()->get("a");
        // return $this->render('default/demo.html.twig', array(
        //     'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        // ));
        // return new response("123");
        // $a  = $this->getRequest()->getSession()->set("c",1000);
        $a  = $this->getRequest()->getSession()->get("c");
        // $this->getRequest()->getSession()->getFlashBag()->add(
        //         "notice",
        //         "you have fatal_error";
        //     );
        dump($a);
        return new response($a);
    }

    /**
     * @Route("/mysql",)
     */
    public function mysqlAction()
    {
        // $user = $this->getUser();
        // $books = $this->getBooks();
        $books = new Books();
        // $id = $books->getId();
        $em = $this->getDoctrine()->getManager();
        $id = $em->getRepository("AppBundle:Books")->findOneBy(array("id"=>1));
        // $id = $user->getId();
        // $em  = $this->getDoctrine()->getEntityManange();
        dump($id);exit;
    }

    /**
     * @Route("/post",name="default_post")
     */
    public function postAction () 
    {
        $demo = $this->getRequest()->isXMLHttpRequest();
        $value = $this->getRequest()->get("a");
        dump($value);
        return new response("Hello world");
    }

    /**
     * @Route("/hello")
     */
    public function helloAction ()
    {
        // return new JsonResponse(array("name"=>"george"));
        return new RedirectResponse("http://www.baidu.com");
    }


    /**
     * @Route("/create",name="default_create")
     */
    public function createAction ()
    {   
        $user = new User();
        $user->setEmail('A Foo Bar');
        $product->setPassword('123456');
        $product->setSex('1');
                
        $em = $this->getDoctrine()->getManager();
                
        $em->persist($product);
        $em->flush();
                
        return new Response('Created product id '.$product->getId());
    }
}
