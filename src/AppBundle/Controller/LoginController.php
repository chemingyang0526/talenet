<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Session\Session;

class LoginController extends Controller {

    /**
    * login form
    *
    * @Route("/login", name="login")
    * @Method({"GET", "POST"})
    */
    public function loginAction(Request $request) {
       
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastEmail = $authenticationUtils->getLastUsername();

        $argsArray = [
            'last_email' => $lastEmail,
            'error'      => $error
        ];

        return $this->render('login/login.html.twig', $argsArray);
    }

    /**
    * logout form
    *
    * @Route("/logout", name="logout")
    * @Method({"GET", "POST"})
    */
    public function logoutAction(Request $request) {
        $session = new Session();
        $session->clear();
    }
}
