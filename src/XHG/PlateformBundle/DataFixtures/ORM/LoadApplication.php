<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use XHG\PlateformBundle\Entity\Application;

/**
 * Description of LoadApplication
 *
 * @author xhg
 */
class LoadApplication extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $applications = $this->container->get('xhg_core.csv_to_array')->convert(dirname(__FILE__) . '/Resources/application.csv');

        foreach ($applications as $key => $application) {
            $app = new Application();
            $app->setContent($application['content']);
            $app->setAuthor($this->getReference('user-' . $application['author']));
            $app->setAdvert($this->getReference('advert-' . $application['advert']));

            $manager->persist($app);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 6;
    }

}
