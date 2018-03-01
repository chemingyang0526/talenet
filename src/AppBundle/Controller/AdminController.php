<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;


/**
* Class AdminController
* package AppBundle\Controller
* @Route("/admin")
*/

class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function indexAction(Request $request)
    {
        //$session = new Session();
        //if ($session->has('user')) {
            return $this->render('/admin/index.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            ]);
        //}

        //$session->getFlashBag()->clear();
        //$this->addFlash('error', 'please login before accessing admin');
        //return $this->redirectToRoute('login');
    }
}
