<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use XHG\PlateformBundle\Entity\Advert;

/**
 * Description of LoadAdvert
 *
 * @author xhg
 */
class LoadAdvert extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor($this->getReference('user-1'));
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…");
        $advert->setImage($this->getReference('image-1'));
        
        $manager->persist($advert);
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
    
}
