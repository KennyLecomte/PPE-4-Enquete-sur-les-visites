<?php

namespace Transnat\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TransnatBlogBundle:Default:index.html.twig');
    }
}
