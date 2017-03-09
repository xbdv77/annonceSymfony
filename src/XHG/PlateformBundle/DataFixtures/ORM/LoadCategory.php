<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use XHG\PlateformBundle\Entity\Category;

/**
 * Description of LoadCategory
 *
 * @author xhg
 */
class LoadCategory extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $categories = $this->container->get('xhg_core.csv_to_array')->convert(dirname(__FILE__) . '/Resources/category.csv');

        foreach ($categories as $key => $category) {
            // On crée la catégorie
            $cat = new Category();
            $cat->setName($category['name']);
            // On la persiste
            $this->addReference('category-' . $key, $cat);
            $manager->persist($cat);
        }

        // On déclenche l'enregistrement de toutes les catégories
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }

}
