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
        $this->addReference('superAdmin', $user1);

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
        $this->addReference('user', $user1);

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
        $this->addReference('dameCantine', $user1);

        $user4 = $userManager->createUser();
        $user4->setPlainPassword('laloupe');
        $user4->setEnabled(true);
        $user4->setEmail('bruno@email.com');
        $user4->setRoles(array('ROLE_USER'));
        $user4->setFirstname('Bruno');
        $user4->setLastname('Bob');
        $user4->setPhone('0778909843');
        $userManager->updateUser($user4, true);
        $this->addReference('user2', $user1);

        $user5 = $userManager->createUser();
        $user5->setPlainPassword('jecode');
        $user5->setEnabled(true);
        $user5->setEmail('twig@email.com');
        $user5->setRoles(array('ROLE_USER'));
        $user5->setFirstname('Marine');
        $user5->setLastname('Twig');
        $user5->setPhone('0678434578');
        $userManager->updateUser($user5, true);
        $this->addReference('user3', $user1);

        $user6 = $userManager->createUser();
        $user6->setPlainPassword('ttt');
        $user6->setEnabled(true);
        $user6->setEmail('tata@email.com');
        $user6->setRoles(array('ROLE_USER'));
        $user6->setFirstname('Tony');
        $user6->setLastname('Donatello');
        $user6->setPhone('0678434556');
        $userManager->updateUser($user6, true);
        $this->addReference('user4', $user1);

        $user7 = $userManager->createUser();
        $user7->setPlainPassword('uuu');
        $user7->setEnabled(true);
        $user7->setEmail('tanguy@email.com');
        $user7->setRoles(array('ROLE_USER'));
        $user7->setFirstname('Eric');
        $user7->setLastname('Tanguy');
        $user7->setPhone('0678434853');
        $userManager->updateUser($user7, true);
        $this->addReference('user5', $user1);
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