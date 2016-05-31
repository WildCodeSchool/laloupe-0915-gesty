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
        $user = $userManager->createUser();
        $user->setPlainPassword('admin');
        $user->setEnabled(true);
        $user->setEmail('admin1@email.com');
        $user->setRoles(array('ROLE_SUPER_ADMIN'));
        $user->setFirstname('Admin');
        $user->setLastname('Admin');
        $user->setPhone('0768298272');
        $userManager->updateUser($user, true);
        $this->addReference('superAdmin', $user);

        // Creation du User aaa
        $user = $userManager->createUser();
        $user->setPlainPassword('aaa');
        $user->setEnabled(true);
        $user->setEmail('aaa@email.com');
        $user->setRoles(array('ROLE_USER'));
        $user->setFirstname('Aaa');
        $user->setLastname('Aaa');
        $user->setPhone('0768298272');
        $user->setAdresse('bd Alexandre Martin');
        $user->setCodePostal('45000');
        $user->setCommune('Orléans');
        $user->setModeDePaiement('Cheque');
        $user->setValidation(true);
        $userManager->updateUser($user, true);
        $this->addReference('user', $user);

        // Creation du User damedecantine
        $user = $userManager->createUser();
        $user->setPlainPassword('aaa');
        $user->setEnabled(true);
        $user->setEmail('damedecantine@email.com');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setFirstname('Aaa');
        $user->setLastname('Aaa');
        $user->setPhone('0768298272');
        $userManager->updateUser($user, true);
        $this->addReference('dameCantine', $user);

        $user = $userManager->createUser();
        $user->setPlainPassword('laloupe');
        $user->setEnabled(true);
        $user->setEmail('bruno@email.com');
        $user->setRoles(array('ROLE_USER'));
        $user->setFirstname('Bruno');
        $user->setLastname('Bob');
        $user->setPhone('0778909843');
        $user->setValidation(true);
        $userManager->updateUser($user, true);
        $this->addReference('user2', $user);

        $user = $userManager->createUser();
        $user->setPlainPassword('jecode');
        $user->setEnabled(true);
        $user->setEmail('twig@email.com');
        $user->setRoles(array('ROLE_USER'));
        $user->setFirstname('Marine');
        $user->setLastname('Twig');
        $user->setPhone('0678434578');
        $userManager->updateUser($user, true);
        $this->addReference('user3', $user);

        $user = $userManager->createUser();
        $user->setPlainPassword('ttt');
        $user->setEnabled(true);
        $user->setEmail('tata@email.com');
        $user->setRoles(array('ROLE_USER'));
        $user->setFirstname('Tony');
        $user->setLastname('Donatello');
        $user->setPhone('0678434556');
        $userManager->updateUser($user, true);
        $this->addReference('user4', $user);

        $user7 = $userManager->createUser();
        $user7->setPlainPassword('uuu');
        $user7->setEnabled(true);
        $user7->setEmail('tanguy@email.com');
        $user7->setRoles(array('ROLE_USER'));
        $user7->setFirstname('Eric');
        $user7->setLastname('Tanguy');
        $user7->setPhone('0678434853');
        $userManager->updateUser($user7, true);
        $this->addReference('user5', $user7);
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