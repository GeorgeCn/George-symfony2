<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number")
     */
    public function numberAction()
    {
        $number = mt_rand(0, 100);

        // return new Response(
        //     '<html><body>Lucky number: '.$number.'</body></html>'
        // );
        return $this->render('lucky/number.html.twig', array(
            'number' => $number,
        ));
    }

     /**
     * @Route("/lucky/numbers/{count}")
     */
    public function numbersAction($count)
    {
        $numbers = array();
        for ($i = 0; $i < $count; $i++) {
            $numbers[] = rand(0, 100);
        }
        $numbersList = implode(', ', $numbers);
 
        return new Response(
            '<html><body>Lucky numbers: '.$numbersList.'</body></html>'
        );
    }

    /**
     * 
     */
    public function ftpAction ()
    {
        return new Response("Hello world");
    }
}