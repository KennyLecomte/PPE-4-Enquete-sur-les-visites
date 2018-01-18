<?php

namespace TobatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TobatBundle:Default:index.html.twig');
    }
}
