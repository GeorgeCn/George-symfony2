<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Endroid\QrCode\QrCode;

/**
 * @Route("/QrCode")
 */
class QrCodeController extends Controller
{
	/**
     * @Route("/qrcode", name="QrCode_qrcode")
     */
	public function qrcodeAction ()
	{
		header("content-type: image/png");
         $qrcode = new QrCode();
         $qrcode ->setText("Hello world")
              ->setSize(300)
              ->setPadding(10)
              ->setErrorCorrection('high')
              ->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0, 'a' => 0])
              ->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0])
              ->setLabel('My Lable')
              ->setLabelFontSize(16)
              ->render()
         ;

		return new Response("Hello World");
	}
}
