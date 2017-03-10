<?php

namespace XHG\PlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use XHG\PlateformBundle\Entity\Advert;
use XHG\PlateformBundle\Entity\Image;
use XHG\PlateformBundle\Entity\Application;

class AdvertController extends Controller
{

    public function indexAction($page)
    {
// On ne sait pas combien de pages il y a
// Mais on sait qu'une page doit être supérieure ou égale à 1
        if ($page < 1) {
// On déclenche une exception NotFoundHttpException, cela va afficher
// une page d'erreur 404 (qu'on pourra personnaliser plus tard d'ailleurs)
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }

        return $this->render('XHGPlateformBundle:Advert:index.html.twig', array(
                    'listAdverts' => $this->getDoctrine()->getManager()->getRepository('XHGPlateformBundle:Advert')->findAll(),
        ));
    }

    public function viewAction($id)
    {
        $advert = $this->getDoctrine()->getManager()->getRepository('XHGPlateformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }
        
        return $this->render('XHGPlateformBundle:Advert:view.html.twig', array(
                    'advert' => $advert,
                    'listApplication' => $this->getDoctrine()->getManager()->getRepository('XHGPlateformBundle:Application')->findByAdvert($advert),
        ));
    }

    public function addAction(Request $request)
    {
        // recupération du service antispam
        $antispam = $this->get('xhg_plateform.antispam');

// La gestion d'un formulaire est particulière, mais l'idée est la suivante :
// Si la requête est en POST, c'est que le visiteur a soumis le formulaire
        if ($request->isMethod('POST')) {
// Ici, on s'occupera de la création et de la gestion du formulaire
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
// Puis on redirige vers la page de visualisation de cettte annonce
            return $this->redirectToRoute('xhg_plateform_view', array('id' => 5));
        }

        // On récupère l'EntityManager
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('\XHG\CoreBundle\Entity\User')->findOneByUsername("xavier");
// Création de l'entité Advert
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor($user);
        $advert->setEmail(('xhaltebourg@umanis.com'));
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");

        // Création de l'entité Image
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

        // On lie l'image à l'annonce
        $advert->setImage($image);
        // Étape 1 : On « persiste » l'entité
        $em->persist($advert);
        // Étape 1 bis : si on n'avait pas défini le cascade={"persist"},
        // on devrait persister à la main l'entité $image
        $em->persist($image);
        
        $application = new Application();
        $application->setAuthor($user);
        $application->setContent('il est content monsieur durand');
        $application->setAdvert($advert);
        $em->persist($application);
        // Étape 2 : On déclenche l'enregistrement
        $em->flush();
// Si on n'est pas en POST, alors on affiche le formulaire
        return $this->render('XHGPlateformBundle:Advert:add.html.twig', array(
                    'antispam' => $antispam,
        ));
    }

    public function editAction($id, Request $request)
    {
// Ici, on récupérera l'annonce correspondante à $id
// Même mécanisme que pour l'ajout
        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('xhg_plateform_view', array('id' => $id));
        }

        $advert = array(
            'title' => 'Recherche développpeur Symfony2',
            'id' => $id,
            'author' => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date' => new \Datetime()
        );

        return $this->render('XHGPlateformBundle:Advert:edit.html.twig', array(
                    'advert' => $advert,
        ));
    }

    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('XHGPlateformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        // On boucle sur les catégories de l'annonce pour les supprimer
        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        $em->detach($advert);
        $em->flush();
        $this->addFlash('notice', 'l\'annonce a bien été supprimé');

        return $this->forward('XHGPlateformBundle:Advert:index', array(
                    'page' => 1,
        ));
    }

    public function menuAction($limit)
    {
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission de webmaster'),
            array('id' => 9, 'title' => 'Offre de stage webdesigner')
        );


        return $this->render('XHGPlateformBundle:Advert:menu.html.twig', array(
                    // Tout l'intérêt est ici : le contrôleur passe
                    // les variables nécessaires au template !
                    'listAdverts' => $this->getDoctrine()->getManager()->getRepository('XHGPlateformBundle:Advert')->getLastAdvert($limit),
        ));
    }

}
