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
use AppBundle\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
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

    //保存数据到数据库中
    /**
     * @Route("/create",name="default_create")
     */
    public function createAction ()
    {   
        $user = new User();
        $user->setEmail('A Foo Bar');
        $user->setPassword('123456');
        $user->setSex('1');
                
        $em = $this->getDoctrine()->getManager();
                
        $em->persist($user);
        $em->flush();
                
        return new Response('Created user id '.$user->getId());//获取创建的id
    }

    //从数据库读取数据
    /**
     * @Route("/read/{id}",name="default_read")
     */
    public function readAction ($id)
    {
        // $em = $this->getDoctrine()->getManager();
        // $user =$em->getRepository('AppBundle:Books')->find($id);
        $em = $this->getDoctrine()->getManager();
        $user =$em->getRepository('AppBundle:Books')->findOneBy(array("id"=>$id));
        // if(!$product){
        //      throw $this->createNotFoundException('No product found for id ' .$id);
        // }
        dump($user);
        return new response('Created user id ');
       //do something,想把$product对象传递给一个template等。
    }

    /**
     * 渲染task表单
     * @Route("/task", name="default_task")
     */
    public function taskAction (Request $request)
    {
        $task = new Task();
        $task->setTask('Write a blog post');
        $task->setDueDate(new \DateTime('tomorrow'));
 
        $form = $this->createFormBuilder($task)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            return $this->redirectToRoute('task_success');
        }
 
        return $this->render('default/task.html.twig', array(
            'form' => $form->createView(),
            'name' => 'George',
        ));
    }
}
