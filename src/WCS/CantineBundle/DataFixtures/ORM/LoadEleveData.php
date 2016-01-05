<?php
// src/WCS/CantineBundle/DataFixtures/ORM/LoadUserData.php

namespace WCS\CantineBundle\DataFixtures\ORM;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Get our userManager, you must implement `ContainerAwareInterface`
        $userManager = $this->getUserManager();

        // Creation du User admin
        $user1 = $userManager->createUser();
        $user1->setPlainPassword('admin');
        $user1->setEnabled(true);
        $user1->setEmail('admin1@email.com');
        $user1->setRoles(array('ROLE_SUPER_ADMIN'));
        $user1->setFirstname('Admin');
        $user1->setLastname('Admin');
        $user1->setPhone('0768298272');
        $userManager->updateUser($user1, true);

        // Creation du User aaa
        $user2 = $userManager->createUser();
        $user2->setPlainPassword('aaa');
        $user2->setEnabled(true);
        $user2->setEmail('aaa@email.com');
        $user2->setRoles(array('ROLE_USER'));
        $user2->setFirstname('Aaa');
        $user2->setLastname('Aaa');
        $user2->setPhone('0768298272');
        $userManager->updateUser($user2, true);

        // Creation du User damedecantine
        $user3 = $userManager->createUser();
        $user3->setPlainPassword('aaa');
        $user3->setEnabled(true);
        $user3->setEmail('damedecantine@email.com');
        $user3->setRoles(array('ROLE_ADMIN'));
        $user3->setFirstname('Aaa');
        $user3->setLastname('Aaa');
        $user3->setPhone('0768298272');
        $userManager->updateUser($user3, true);
    }

    public function getSecurityManager()
    {
        return $this->container->get('security.encoder_factory');
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager()
    {
        return $this->container->get('fos_user.user_manager');
    }

    public function getOrder()
    {
        return 1; // ordre d'appel
    }
}