<?php

namespace XHG\PlateformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use XHG\PlateformBundle\Entity\Advert;
use XHG\PlateformBundle\Form\AdvertType;

class AdvertController extends Controller
{

    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page "' . $page . '" inexistante.');
        }

        $nbPerPage = 3;
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
        $advert = new Advert();
        $form = $this->get('form.factory')->create(AdvertType::class,$advert);
        
        // recupération du service antispam
        $antispam = $this->get('xhg_plateform.antispam');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $advert->setAuthor($user = $em->getRepository('XHGCoreBundle:User')->findOneby(array('username' => 'xavier')));
                $em->persist($advert);
                $em->flush();
            }
            $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
            return $this->redirectToRoute('xhg_plateform_view', array('id' => $advert->getId()));
        }
        
        return $this->render('XHGPlateformBundle:Advert:add.html.twig', array(
                    'antispam' => $antispam,
                    'form' => $form->createView(),
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

        $em->remove($advert);
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

    public function purgeAction($days, Request $request)
    {
        foreach ($this->get('xhg_plateform.purger.advert')->purge($days) as $msg) {
            $request->getSession()->getFlashBag()->add('notice', $msg);
        }
        return $this->redirectToRoute('xhg_plateform_home');
    }

}
