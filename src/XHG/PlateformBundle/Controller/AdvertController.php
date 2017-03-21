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
        if ($page < 1) {
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }

        $nbPerPage = 1;
        $listAdverts = $this->getDoctrine()->getManager()->getRepository('XHGPlateformBundle:Advert')->getAdverts($page, $nbPerPage);
        $nbPage = ceil(count($listAdverts) / $nbPerPage);

        return $this->render('XHGPlateformBundle:Advert:index.html.twig', array(
                    'listAdverts' => $listAdverts,
                    'nbPages' => $nbPage,
                    'page' => $page,
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
                    'listAdvertSkills' => $this->getDoctrine()->getManager()->getRepository('XHGPlateformBundle:AdvertSkill')->findByAdvert($advert),
        ));
    }

    public function addAction(Request $request)
    {
        // recupération du service antispam
        $antispam = $this->get('xhg_plateform.antispam');

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            return $this->redirectToRoute('xhg_plateform_view', array('id' => 5));
        }

        return $this->render('XHGPlateformBundle:Advert:add.html.twig', array(
                    'antispam' => $antispam,
        ));
    }

    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $advert = $em->getRepository('XHGPlateformBundle:Advert')->find($id);

        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce d'id " . $id . " n'existe pas.");
        }

        if ($request->isMethod('POST')) {
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');

            return $this->redirectToRoute('xhg_plateform_view', array('id' => $advert->getId()));
        }

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
        return $this->render('XHGPlateformBundle:Advert:menu.html.twig', array(
                    'listAdverts' => $this->getDoctrine()
                            ->getManager()
                            ->getRepository('XHGPlateformBundle:Advert')
                            ->getLastAdvert($limit),
        ));
    }

}
