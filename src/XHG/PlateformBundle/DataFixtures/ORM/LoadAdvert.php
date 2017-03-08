<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use XHG\PlateformBundle\Entity\Advert;

/**
 * Description of LoadAdvert
 *
 * @author xhg
 */
class LoadAdvert extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $adverts = $this->container->get('xhg_core.csv_to_array')->convert(dirname(__FILE__) . '/Resources/advert.csv');

        foreach ($adverts as $key => $advert) {
            $ad = new Advert();
            $ad->setTitle($advert['title']);
            $ad->setContent($advert['content']);
            $ad->setAuthor($this->getReference('user-' . $advert['author']));
            $ad->setImage($this->getReference('image-' . $advert['image']));

            $this->addReference('advert-' . $key, $ad);
            
            $manager->persist($ad);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }

}
