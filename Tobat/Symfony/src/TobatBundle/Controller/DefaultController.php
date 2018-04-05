<?php

namespace TobatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TobatBundle\Entity\Bateau;
use TobatBundle\Entity\Connexion;
use TobatBundle\Entity\CategorieSociale;
use TobatBundle\Entity\Departement;
use TobatBundle\Entity\Enquete;
use TobatBundle\Form\ConnexionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        if(!$this->get('session')->isStarted())
        {
            $this->creationSession();
        }

        if(!$this->verificationConnexion())
        {
            $connexion2 = new Connexion();
            $managerConnexion = $this->getDoctrine()->getManager();
            $connexion = $managerConnexion->getRepository('TobatBundle:Connexion')->find(1);
            $login = $connexion->getLogin();
            $mdp = $connexion->getMotDePasse();

            $form = $this->get('form.factory')->create(ConnexionType::class, $connexion2);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                if ($connexion2->getLogin() == $connexion->getLogin() && $connexion2->getMotDePasse() == $connexion->getMotDePasse()) {
                    $em = $this->getDoctrine()->getManager();
                    echo('Vous êtes bien connectés');

                     $this->get('session')->set('status', 'connecte');

                    return $this->render('TobatBundle:Default:index.html.twig');
                }
                else {
                    echo('Mauvais login ou mot de passe');
                    return $this->render('TobatBundle:Default:connexion.html.twig', array('form' => $form->createView()));
                }
            }

            return $this->render('TobatBundle:Default:connexion.html.twig', array('form' => $form->createView()));
        }

        return $this->render('TobatBundle:Default:index.html.twig');
    }

    public function creationSession()
    {
        $session = new Session();
        $session->start();
    }

    public function verificationConnexion()
    {
        $connecte = false;

        if($this->get('session')->get('status')=='connecte')
        {
            $connecte = true;
        }

        return $connecte;
    }

    public function testAction()
    {
        // Instance de Serializer
        //$serializer = new Serializer(array(new getSetMethodNormalizer()),array(new XMLEncoder(),new JsonEncoder()));
        // Instance du manager de Doctrine
        //$manager = $this->getDoctrine()->getManager();
        // Récupération du client
        //$client = $manager->getRepository(Bateau::class)->findAll();

        //return new Response($serializer->serialize($client, 'json'));

        $entityManager = $this->getDoctrine()->getManager();

        /*$enquete = $entityManager->getRepository('TobatBundle:Enquete')->find(1);

        $bateauEnquete=$enquete->getBateaux();
        foreach ($bateauEnquete as $bateau) 
        {
            var_dump($bateau);
        }*/

        $bateau = $entityManager->getRepository('TobatBundle:Bateau')->find(1);
        $enqueteBateau = $bateau->getEnquetes();
        foreach ($enqueteBateau as $enquete) 
        {
            var_dump($enquete);
        }
    }

    public function insertEnqueteAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $ville = $_POST['ville'];
         $codePostal = $_POST['codePostal'];
         $raison = $_POST['raison'];
         $budget = $_POST['budget'];
         $trancheAge = $_POST['trancheAge'];
         $vip = $_POST['vip'];
         $categorieSociale = $_POST['categorieSociale'];

        if(empty($ville) || empty($codePostal) || empty($raison) || empty($budget) || empty($trancheAge) || empty($vip) || empty($categorieSociale))
        {
            echo 'Veuillez remplir tous les champs';
        }

        if($vip=='true')
        {
            $vipBool = TRUE;
        }
        else
        {
            $vipBool = FALSE;
        }

        $idDepartement = substr($codePostal, 0, 2);

        $minMax = explode("-", $budget);

        $min = $minMax[0];
        $max = $minMax[1];

        $budget = $entityManager->getRepository('TobatBundle:Budget')->findByMin($min);

        $categorieSociale = $entityManager->getRepository('TobatBundle:CategorieSociale')->findByNomCategorie($categorieSociale);

        $departement = $entityManager->getRepository('TobatBundle:Departement')->findById($idDepartement);

        $dateEnquete = new \DateTime();
        $dateEnquete->format('Y-m-d H:i:s');

        $codePostal=intval($codePostal);

        $enquete = new Enquete();

        $enquete->setTrancheAge($trancheAge);
        $enquete->setMotivation($raison);
        $enquete->setVille($ville);
        $enquete->setCodePostal($codePostal);
        $enquete->setVip($vipBool);
        $enquete->setDateEnquete($dateEnquete);
        $enquete->setBudget($budget[0]);
        $enquete->setDepartement($departement[0]);
        $enquete->setCategorieSociale($categorieSociale[0]);

        var_dump($enquete->getTrancheAge());
        var_dump($enquete->getMotivation());
        var_dump($enquete->getVille());
        var_dump($enquete->getCodePostal());
        var_dump($enquete->getVip());
        var_dump($enquete->getDateEnquete());
        var_dump($enquete->getBudget()->getMin());
        var_dump($enquete->getDepartement()->getNom());
        var_dump($enquete->getCategorieSociale()->getNomCategorie());

        $entityManager->persist($enquete);
        $entityManager->flush();

        $reponse = 'Enquete bien ajoutee';

        return new Response($reponse);
    }

    public function insertBateauAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $modele = json_decode($request->getContent(), true)['modele'];
        $categorie = json_decode($request->getContent(), true)['categorie'];

        if(empty($modele) || empty($categorie))
        {
            echo 'Veuillez remplir tous les champs';
        }

        $bateau = new Bateau();

        $bateau->setModele($modele);
        $bateau->setCategorie($categorie);

        $entityManager->persist($bateau);
        $entityManager->flush();

        return new Response('Bateau bien ajoute');
    }

    public function getBudgetsJSONAction()
    {
        $budgets = array();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $entityManager = $this->getDoctrine()->getManager();

        $budgetsObjets = $entityManager->getRepository('TobatBundle:Budget')->findAll();

        foreach ($budgetsObjets as $budget) 
        {            
            $temp=['id'=>$budget->getId(), 'min'=>$budget->getMin(), 'max'=>$budget->getMax()];

            array_push($budgets, $temp);
        }

        return new Response($serializer->serialize($budgets, 'json'));
    }

    public function getCategoriesSocialesJSONAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $categoriesSociales = array();

        $categoriesSocialesObjets = $entityManager->getRepository('TobatBundle:CategorieSociale')->findAll();

        foreach ($categoriesSocialesObjets as $categorieSociale) 
        {
            $temp=['id'=>$categorieSociale->getId(), 'nomCategorie'=>$categorieSociale->getNomCategorie()];

            array_push($categoriesSociales, $temp);
        }

        return new Response($serializer->serialize($categoriesSociales, 'json'));
    }

    public function getBateauxJSONAction()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $bateaux = array();

        $bateauxObjets = $entityManager->getRepository('TobatBundle:Bateau')->findAll();

        foreach ($bateauxObjets as $bateau) 
        {
            $temp=['id'=>$bateau->getId(), 'modele'=>$bateau->getModele()];

            array_push($bateaux, $temp);
        }

        return new Response($serializer->serialize($bateaux, 'json'));
    }

    public function getCategoriesBateaux()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $query = $entityManager->createQuery('SELECT DISTINCT b.categorie FROM TobatBundle:Bateau b');

        $categoriesBateaux = $query->getResult();

        return $categoriesBateaux;
    }

    public function getEnquetes()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $enquetes = $entityManager->getRepository('TobatBundle:Enquete')->findAll();

        return $enquetes;
    }

    public function getClassementCategoriesBateaux()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $classementCategories = [];

        $categoriesBateaux = $this->getCategoriesBateaux();
        foreach ($categoriesBateaux as $categorieBateaux) 
        {
            $classementCategories += [$categorieBateaux['categorie'] => 0];
        }

        $categoriesBateaux = $this->getCategoriesBateaux();

        $enquetes = $this->getEnquetes();

        foreach ($enquetes as $enquete) 
        {
            $bateauxEnquete = $enquete->getBateaux();
            foreach ($bateauxEnquete as $bateauEnquete ) 
            {
                $classementCategories[$bateauEnquete->getCategorie()] +=1;
            }
        }

        arsort($classementCategories);

        return $classementCategories;
    }

    public function getClassementBateauxCategorie($categorie)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $classementBateaux = [];

        $bateauxCategorie = $entityManager->getRepository('TobatBundle:Bateau')->findByCategorie($categorie);

        foreach ($bateauxCategorie as $bateauCategorie) 
        {
            $classementBateaux+=[$bateauCategorie->getModele() => $bateauCategorie->getEnquetes()->count()];
        }

        arsort($classementBateaux);

        $keys = array_keys($classementBateaux);

        $classementBateaux+=['keys' => $keys];

        return $classementBateaux;
    }

    public function getClassementsBateauxCategories()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $classementsBateauxCategories = [];

        $categories = $this->getCategoriesBateaux();
        foreach ($categories as $categorie) 
        {
            $classementBateauxCategorie = $this->getClassementBateauxCategorie($categorie);
            $classementsBateauxCategories += [$categorie['categorie'] => $classementBateauxCategorie];
        }

        return $classementsBateauxCategories;
    }

    public function getClassementBateauxAction()
    {
        if($this->verificationConnexion())
        {
            $classementCategoriesBateaux = $this->getClassementCategoriesBateaux();
            $classementsBateauxCategories = $this->getClassementsBateauxCategories();

            $categoriesBateaux = array_keys($classementCategoriesBateaux);

            return $this->render('TobatBundle:Default:classementBateaux.html.twig', array('classementCategories' => $classementCategoriesBateaux, 'categoriesBateaux' => $categoriesBateaux, 'classementsBateauxCategories' => $classementsBateauxCategories));
        }
        else
        {
            return $this->redirectToRoute('');
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
        if(!$this->verificationConnexion())
        {

            $nbVisiteursTranchesAge = $this->getNbVisiteursTranchesAge();

            $nbVisiteursCategories = $this->getNbVisiteursCategoriesSociales();

            $nbVisiteursMotivations = $this->getNbVisiteursMotivations();

            return $this->render('TobatBundle:Default:nbVisiteursCritere.html.twig', array('nbVisiteursCategories' => $nbVisiteursCategories, 'nbVisiteursTranchesAge' => $nbVisiteursTranchesAge, 'nbVisiteursMotivations' => $nbVisiteursMotivations));
        }
        else
        {
            return $this->redirectToRoute('');
        }
    }

    public function enqueteAction()
    {
        if($this->verificationConnexion())
        {
            return $this->render('TobatBundle:Default:enquete.html.twig');
        }
        else
        {
            return $this->redirectToRoute('');
        }
    }
    public function repartitionVisiteurAction()
    {
        if(!$this->verificationConnexion())
        {
            return $this->render('TobatBundle:Default:repartition.html.twig');
        }
        else
        {
            return $this->redirectToRoute('');
        }
    }

    public function budgetAction()
    {
        if(!$this->verificationConnexion())
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
        else
        {
            return $this->redirectToRoute('');
        }
    }

    public function getNbEnqueteAction(Request $request){

        if(!$this->verificationConnexion())
        {
            $date = $request->get('date');

            $em = $this->getDoctrine()->getManager();
            $connection = $em->getConnection();
            $queryNb = $connection->prepare("SELECT count(*) FROM enquete WHERE dateEnquete = :date");
            $queryNb->bindValue('date', $date);
            $queryNb->execute();
            $nbEnquetes = $queryNb->fetchAll();

            return $this->render('TobatBundle:Default:nbEnquetes.html.twig',array('nbEnquetes' => $nbEnquetes));
        }
        else
        {
            return $this->redirectToRoute('');
        }

    }
}

