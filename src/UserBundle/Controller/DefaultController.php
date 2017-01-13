<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        if ($this->isGranted("'IS_AUTHENTICATED_FULLY'")){
            return $this->redirect($this->generateUrl(""));
        }
        else
            return $this->redirect($this->generateUrl("fos_user_security_login"));
    }
}
