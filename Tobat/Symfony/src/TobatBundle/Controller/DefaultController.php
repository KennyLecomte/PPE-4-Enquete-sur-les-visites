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

    public function getCategoriesBateaux()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQuery('SELECT DISTINCT b.categorie FROM TobatBundle:Bateau b');

        $categoriesBateaux = $query->getResult();

        return $categoriesBateaux;
    }

    public function getClassementBateauxAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $categoriesBateaux = $this->getCategoriesBateaux();

        foreach ($categoriesBateaux as $categorieBateau) 
        {
            $query = $entityManager->createQuery(
            'SELECT count(e)
            FROM TobatBundle:Enquete e, TobatBundle:Bateau b
            WHERE e.budget=c.id AND c.nomCategorie=:categorie'
            
            )->setParameter('categorie', $categorieSociale->getNomCategorie());
        }
    }

    public function getNbVisiteursCategoriesSociales()
    {
        $nbVisiteursCategories = array();

        $entityManager = $this->getDoctrine()->getManager();

        $categoriesSociales = $entityManager->getRepository('TobatBundle:CategorieSociale')->findAll();

        foreach ($categoriesSociales as $categorieSociale) 
        {
            $query = $entityManager->createQuery(
            'SELECT count(e)
            FROM TobatBundle:Enquete e, TobatBundle:CategorieSociale c
            WHERE e.budget=c.id AND c.nomCategorie=:categorie'
            
            )->setParameter('categorie', $categorieSociale->getNomCategorie());

            $resultat = $query->getResult();

            $nbVisiteursCategorie = array('nomCategorie'=>$categorieSociale->getNomCategorie(), 'nbVisiteurs' =>$resultat[0][1]);

            array_push($nbVisiteursCategories, $nbVisiteursCategorie);
        }

        return $nbVisiteursCategories;
    }

    public function getNbVisiteursTranchesAge()
    {
        $nbVisiteursTranchesAge = array();

        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQuery(
            'SELECT DISTINCT(e.trancheAge)
            FROM TobatBundle:Enquete e'
        );

        $tranchesAge = $query->getResult();

        foreach ($tranchesAge as $trancheAge) 
        {
            $query = $entityManager->createQuery(
            'SELECT count(e)
            FROM TobatBundle:Enquete e
            WHERE e.trancheAge=:trancheAge'
            
            )->setParameter('trancheAge', $trancheAge);

            $resultat = $query->getResult();

            $nbVisiteursTrancheAge = array('trancheAge'=>$trancheAge, 'nbVisiteurs' =>$resultat[0][1]);

            array_push($nbVisiteursTranchesAge, $nbVisiteursTrancheAge);
        }

        return $nbVisiteursTranchesAge;
    }

    public function getNbVisiteursMotivations()
    {
        $nbVisiteursMotivations = array();

        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQuery(
            'SELECT DISTINCT(e.motivation)
            FROM TobatBundle:Enquete e'
        );

        $motivations = $query->getResult();

        foreach ($motivations as $motivation) 
        {
            $query = $entityManager->createQuery(
            'SELECT count(e)
            FROM TobatBundle:Enquete e
            WHERE e.motivation=:motivation'
            
            )->setParameter('motivation', $motivation);

            $resultat = $query->getResult();

            $nbVisiteursMotivation = array('motivation'=>$motivation, 'nbVisiteurs' =>$resultat[0][1]);

            array_push($nbVisiteursMotivations, $nbVisiteursMotivation);
        }

        return $nbVisiteursMotivations;
    }

    public function nbVisiteursCritereAction()
    {
        $nbVisiteursTranchesAge = $this->getNbVisiteursTranchesAge();

        $nbVisiteursCategories = $this->getNbVisiteursCategoriesSociales();

        $nbVisiteursMotivations = $this->getNbVisiteursMotivations();

        return $this->render('TobatBundle:Default:nbVisiteursCritere.html.twig', array('nbVisiteursCategories' => $nbVisiteursCategories, 'nbVisiteursTranchesAge' => $nbVisiteursTranchesAge, 'nbVisiteursMotivations' => $nbVisiteursMotivations));
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

