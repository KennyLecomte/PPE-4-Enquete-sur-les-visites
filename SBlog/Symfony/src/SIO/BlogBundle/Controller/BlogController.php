<?php

namespace SIO\BlogBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use SIO\BlogBundle\Entity\Article;

class BlogController extends Controller
{

public function getArticles()
{
  $managerArticle = $this->getDoctrine()->getManager();

  $articles = $managerArticle->getRepository('SIOBlogBundle:Article')->findAll();

  return $articles;
}

	public function indexAction()
{
  
  $articles = $this->getArticles();
    
  // Puis modifiez la ligne du render comme ceci, pour prendre en compte nos articles :
  return $this->render('SIOBlogBundle:Blog:index.html.twig', array(
    'articles' => $articles
  ));
}



	public function voirAction($id)
{
  
  $managerArticle = $this->getDoctrine()->getManager();

  $article = $managerArticle->getRepository('SIOBlogBundle:Article')->find($id);
    
  // Puis modifiez la ligne du render comme ceci, pour prendre en compte l'article :
  return $this->render('SIOBlogBundle:Blog:voir.html.twig', array(
    'article' => $article
  ));
}

  	public function ajouterAction(Request $request)
  	{

      $article = new Article();
      $article->setTitre("Article test");
      $article->setContenu("Ceci est un article de test");
      $article->setAuteur("Aklzpekgk");

    	$em = $this->getDoctrine()->getManager();
      $em->persist($article);
      $em->flush();

      if($request->isMethod('POST'))
      {
        return $this->redirectToRoute('sio_blog_voir', array('id' => $article->getId()));
      }

      return $this->render('SIOBlogBundle:Blog:voir.html.twig', array(
        'article' => $article
      ));

 	  }

 	public function modifierAction($id, Request $request)
 	 {
 	 	    // Ici, on récupérera l'article correspondant à l'$id

    		// Ici, on s'occupera de la création et de la gestion du formulaire
   		   return $this->render('SIOBlogBundle:Blog:modifier.html.twig');
    }

    public function supprimerAction($id)
    {
      $managerArticle = $this->getDoctrine()->getManager();

      $article = $managerArticle->getRepository('SIOBlogBundle:Article')->find($id);

      $managerArticle->remove($article);
      $managerArticle->flush();

    

      return $this->redirectToRoute('sio_blog_accueil');

    } 

    public function voirFichierAction($nom, $type, $date, Request $request){
    	$auteur=$request->query->get('auteur');
    	$cat=$request->query->get('cat');
    	return new Response ("Affichage du fichier ".$nom. " du format ".$type." créé en ".$date." par ".$auteur." de la catégorie ".$cat);
    }

    public function menuAction() 
    {
      // On fixe en dur une liste ici, bien entendu par la suite on la récupérera depuis la BDD !
      // On pourra récupérer $nombre articles depuis la BDD, avec $nombre un paramètre qu'on peut changer lorsqu'on appelle cette action
      $liste = array(
        array('id' => 2, 'titre' => 'Voyage en Angleterre !'),
        array('id' => 5, 'titre' => 'Test sur Symfony'),
        array('id' => 9, 'titre' => 'Competences de stages')
      );
      
      return $this->render('SIOBlogBundle:Blog:menu.html.twig', array(
        'liste_articles' => $liste // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaires au template !
      ));
    }

    public function testAction()
    {
        $managerArticle = $this->getDoctrine()->getManager();

        $article = $managerArticle->getRepository('SIOBlogBundle:Article')->find(3);

        $article->setContenu('Vous allez réussir ces épreuves !!');
        $managerArticle->flush();

        return $this->render('SIOBlogBundle:Blog:test.html.twig', array('article' => $article));
    }
}