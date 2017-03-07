<?php

namespace XHG\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadCategory
 *
 * @author xhg
 */
class LoadUser extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
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
        // Get our userManager, you must implement `ContainerAwareInterface`
        $userManager = $this->container->get('fos_user.user_manager');

        //lecture du fichier csv source        
        $users = $this->container->get('xhg_core.csv_to_array')->convert(dirname(__FILE__) . '/Resources/user.csv');

        foreach ($users as $key => $userToLoad) {
            // Create our user and set details
            $user = $userManager->createUser();
            $user->setUsername($userToLoad['username']);
            $user->setEmail($userToLoad['email']);
            $user->setPlainPassword($userToLoad['password']);
            //$user->setPassword('3NCRYPT3D-V3R51ON');
            $user->setEnabled(true);
            $user->setRoles(array($userToLoad['role']));

            // ajout de l'user dans les references pour le load des autres entités
            $this->addReference("user-$key", $user);
            // Update the user
            $userManager->updateUser($user, true);
        }
    }

    public function getOrder()
    {
        return 1;
    }

}
