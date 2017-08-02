<?php

namespace George\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/qq/1")
     */
    public function indexAction()
    {
    	var_dump(222);
        // return $this->render('GeorgeWebBundle:Default:index.html.twig');
    }
}
