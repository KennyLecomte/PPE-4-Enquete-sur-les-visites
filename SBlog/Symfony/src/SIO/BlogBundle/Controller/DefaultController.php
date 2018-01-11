<?php

namespace SIO\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($nom)
    {
        return $this->render('SIOBlogBundle:Default:index.html.twig',
        array('nom' => $nom));
    }

    public function bonjourAction($nom)
    {
        return $this->render('SIOBlogBundle:Default:bonjour.html.twig',
        array('nom' => $nom));
    }

    public function slamAction()
    {
        return $this->render('SIOBlogBundle:Default:slam.html.twig');
    }

    public function contactAction()
    {
        return $this->render('SIOBlogBundle:Default:contact.html.twig');
    }

    public function blogAction()
    {
        return $this->render('SIOBlogBundle:Default:blogs.html.twig');
    }
}
