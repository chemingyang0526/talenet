<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number/{max}", name="luckynumber")
     */
    public function numberAction(Request $request, $max = 10)
    {
        $number = mt_rand(0,$max);
        // replace this example code with whatever you need
        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}
