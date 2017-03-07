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
        $adverts = array(
            array(
                'title' => 'Recherche développeur Symfony.',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…'
            ),
            array(
                'title' => 'Mission de webmaster',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…'
            ),
            array(
                'title' => 'Offre de stage webdesigner',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
            ),
        );

        foreach ($adverts as $key => $advert) {
            $ad = new Advert();
            $ad->setTitle($advert['title']);
            $ad->setContent($advert['content']);
            $ad->setAuthor($this->getReference('user-1'));
            $ad->setImage($this->getReference("image-$key"));
            
            $manager->persist($ad);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }

}
