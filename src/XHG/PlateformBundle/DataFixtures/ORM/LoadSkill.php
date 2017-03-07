<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use XHG\PlateformBundle\Entity\Skill;

/**
 * Description of LoadSkill
 *
 * @author xhg
 */
class LoadSkill implements FixtureInterface, OrderedFixtureInterface, ContainerAwareInterface
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
        $skills = $this->container->get('xhg_core.csv_to_array')->convert(dirname(__FILE__) . '/Resources/skill.csv');

        foreach ($skills as $skill) {
            $sk = new Skill();
            $sk->setName($skill['name']);

            $manager->persist($sk);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }

}
