<?php

namespace Galmi\XacmlBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GalmiXacmlBundle:Default:index.html.twig');
    }
}
