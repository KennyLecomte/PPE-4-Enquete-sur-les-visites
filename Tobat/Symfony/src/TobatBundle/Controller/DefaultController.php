<?php

namespace TobatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TobatBundle\Entity\Connexion;
use Symfony\Component\Serializer\Serializer;
use TobatBundle\Entity\Bateau;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('TobatBundle:Default:index.html.twig');
    }

    public function testAction()
    {
        // Instance de Serializer
        $serializer = new Serializer(array(new getSetMethodNormalizer()),array(new XMLEncoder(),new JsonEncoder()));
        // Instance du manager de Doctrine
        $manager = $this->getDoctrine()->getManager();
        // Récupération du client
        $client = $manager->getRepository(Bateau::class)->findAll();

        return new Response($serializer->serialize($client, 'json'));
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

