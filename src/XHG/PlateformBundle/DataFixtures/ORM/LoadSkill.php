<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use XHG\PlateformBundle\Entity\Skill;

/**
 * Description of LoadSkill
 *
 * @author xhg
 */
class LoadSkill implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = array(
            'PHP', 
            'Symfony',
            'Zend',
            'JQuery',
            'Bootstrap',
        );
        
        foreach ($names as $name) {
            $skill = new Skill();
            $skill->setName($name);
            
            $manager->persist($skill);
        }
        
        $manager->flush();
    }
}
