<?php

namespace XHG\PlateformBundle\Services\Purger;

use Doctrine\ORM\EntityManager;
use XHG\PlateformBundle\Entity\Advert;
use XHG\PlateformBundle\Entity\Image;

class XHGAdvertPurger
{

    /**
     * @var integer
     */
    private $daysToPurge;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct(EntityManager $em, $days)
    {
        $this->em = $em;
        $this->daysToPurge = $days;
    }

    public function purge($days)
    {
        if ($days === null) {
            $days = $this->daysToPurge;
        }
        $date = new \DateTime($days.' days ago');
        
        $mgs = array();
        foreach ($this->em->getRepository('XHGPlateformBundle:Advert')->getAdvertsBefore($date) as $advert) {
            $mgs[] = "image  supprimée [" . $advert->getImage()->getId() . "]";
            $this->em->remove($advert->getImage());
            $mgs[] = "annonce  supprimée [" . $advert->getId() . "]";
            $this->em->remove($advert);
        }
        $this->em->flush();
        
        return $mgs;
    }

}
