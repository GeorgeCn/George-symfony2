<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{
	/**
    * @Route("/show/{slug}", name="blog_show")
	*/
    public function showAction($slug)
    {
        echo User::echoType('text');
        return new response($slug);
    }
}
