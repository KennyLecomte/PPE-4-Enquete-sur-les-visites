<?php

namespace TobatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TobatBundle\Entity\Bateau;
use TobatBundle\Entity\Connexion;
use TobatBundle\Entity\CategorieSociale;
use TobatBundle\Entity\Departement;
use TobatBundle\Entity\Enquete;
use Symfony\Component\Serializer\Serializer;
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
        $managerBudget = $this->getDoctrine()->getManager();

        $budget = $managerBudget->getRepository('TobatBundle:Budget')->find(1);
        $budgets = $managerBudget->getRepository('TobatBundle:Budget')->findAll();
        $nbVisiteursBudgets = array();

        foreach ($budgets as $budget)
        {
            $query = $managerBudget->createQuery(
                'SELECT COUNT(e) 
                FROM TobatBundle:Enquete e, TobatBundle:Budget b 
                WHERE e.budget = b.id 
                AND b.min = :min and b.max = :max'
            )->setParameter('min', $budget->getMin())->setParameter('max', $budget->getMax());

            $resultats = $query->getResult();

            $nbVisiteursBudget = array('nbVisiteursBudget' => $resultats[0][1]);
            array_push($nbVisiteursBudgets, $nbVisiteursBudget);
        }

        $enquete = $managerBudget->getRepository('TobatBundle:Enquete')->find(1);
        $enquetes = $managerBudget->getRepository('TobatBundle:Enquete')->findAll();

        return $this->render('TobatBundle:Default:budget.html.twig', array('budget' => $budget, 'budgets' => $budgets, 'enquete' => $enquete, 'enquetes' => $enquetes, 'nbVisiteursBudgets' => $nbVisiteursBudgets));
    }

    public function classementAction()
    {
        return $this->render('TobatBundle:Default:classement.html.twig');
    }
}

