<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use XHG\PlateformBundle\Entity\Image;

/**
 * Description of LoadImage
 *
 * @author xhg
 */
class LoadImage extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $images = array(
            array('http://fr.freepik.com/photos-libre/femme-souriante-avec-un-cafe-et-un-ordinateur-portable_1039999.htm','femme qui sourie'),
            array('http://fr.freepik.com/photos-libre/l-39-homme-avec-des-lunettes-et-les-mains-jointes_928676.htm','homme lunette'),
            array('https://www.petitfute.com/medias/mag/6323/835/4970-france-job-de-reve.jpg','job de reve'),
        );

        foreach ($images as $key => $imageToLoad) {
            $image = new Image();
            $image->setUrl($imageToLoad[0]);
            $image->setAlt($imageToLoad[1]);

            $this->addReference("image-$key", $image);
            $manager->persist($image);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
