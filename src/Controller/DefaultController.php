<?php

namespace MMC\SonataAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MMCSonataAdminBundle:Default:index.html.twig');
    }
}
