<?php

namespace TobatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TobatBundle:Default:index.html.twig');
    }

    public function enqueteAction()
    {
    	return $this->render('TobatBundle:Default:enquete.html.twig');
    }

    public function repartitionVisiteurAction()
    {
    	return $this->render('TobatBundle:Default:repartition.html.twig');
    }

    public function visiteursAction()
    {
    	return $this->render('TobatBundle:Default:visiteurs.html.twig');
    }

    public function budgetAction()
    {
    	return $this->render('TobatBundle:Default:budget.html.twig');
    }

    public function classementAction()
    {
    	return $this->render('TobatBundle:Default:classement.html.twig');
    }
}
