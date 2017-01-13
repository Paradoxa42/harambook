<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        if ($this->isGranted("ROLE_USER")){
            return $this->redirect($this->generateUrl("dash_board_homepage"));
        }
        else
            return $this->redirect($this->generateUrl("fos_user_security_login"));
    }
}
