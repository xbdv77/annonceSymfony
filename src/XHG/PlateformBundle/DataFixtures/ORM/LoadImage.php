<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use XHG\PlateformBundle\Entity\Image;

/**
 * Description of LoadImage
 *
 * @author xhg
 */
class LoadImage extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        //lecture du fichier csv source        
        $images = $this->container->get('xhg_core.csv_to_array')->convert(dirname(__FILE__) . '/Resources/image.csv');

        foreach ($images as $key => $image) {
            $img = new Image();
            $img->setUrl($image['url']);
            $img->setAlt($image['alt']);

            $this->addReference("image-$key", $img);
            $manager->persist($img);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}
