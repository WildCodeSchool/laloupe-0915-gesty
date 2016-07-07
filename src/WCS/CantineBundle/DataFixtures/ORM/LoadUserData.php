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
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        /**
         * @var \Application\Sonata\UserBundle\Entity\User $user
         */
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
        $userManager->updateUser($user);
        $this->addReference('superAdmin', $user);

        $parents = [
          
            ['aaa', 'true', 'dupontel@email.fr',    'm', 'Michel', 'Dupontel','rue de la gare', '28240', 'La Loupe', 'true'],
            ['aaa', 'true', 'robert@email.fr',      'f', 'Valerie', 'Robert','rue de la place', '28240', 'La Loupe', 'true'],
            ['aaa', 'true', 'larissa@email.fr',     'm', 'Antoine', 'Larissa','rue du puit', '28240', 'La Loupe', 'true'],
            ['aaa', 'true', 'veron@email.fr',       'm', 'Thomas', 'Veron','rue de la potence', '28240', 'La Loupe', 'true'],
            ['aaa', 'true', 'batista@email.fr',     'f', 'Laurine', 'Batista','rue des vergers', '28240', 'La Loupe',  'true'],
            ['aaa', 'true', 'bouteiller@email.fr',  'm', 'André', 'Bouteiller','lieu dit des noyers', '28240', 'La Loupe', 'true'],
            ['aaa', 'true', 'butin@email.fr',       'm', 'Gregory', 'Butin','rue des artistes', '28240', 'La Loupe', 'true'],
            ['aaa', 'true', 'dorel@email.fr',       'm', 'Louis', 'Dorel','rue du pont', '28240', 'La Loupe', 'true'],
            ['aaa', 'true', 'nelon@email.fr',       'm', 'Maurice', 'Nelon','rue de la gare', '28240', 'La Loupe', 'true'],
            

        ];

        foreach ($parents as $parent) {
            $user = $userManager->createUser();
            $user->setPlainPassword($parent[0]);
            $user->setEnabled($parent[1]);
            $user->setEmail($parent[2]);
            $user->setRoles(array('ROLE_USER'));
            $user->setGender($parent[3]);
            $user->setFirstname($parent[4]);
            $user->setLastname($parent[5]);
            $user->setPhone('0201010101');
            $user->setAdresse($parent[6]);
            $user->setCodePostal($parent[7]);
            $user->setCommune($parent[8]);
            $user->setModeDePaiement('Cheque');
            $user->setValidation(true);
            $userManager->updateUser($user);
            $this->addReference($parent[5], $user);

        }

        
        // Creation du User employe de service
        $user = $userManager->createUser();
        $user->setPlainPassword('aaa');
        $user->setEnabled(true);
        $user->setEmail('employe@email.com');
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setFirstname('Aaa');
        $user->setLastname('Aaa');
        $user->setPhone('0768298272');
        $userManager->updateUser($user);
        $this->addReference('employe', $user);

        // Creation du User employe CANTINE seule
        $user = $userManager->createUser();
        $user->setPlainPassword('aaa');
        $user->setEnabled(true);
        $user->setEmail('cantine@email.com');
        $user->setRoles(array('ROLE_CANTINE'));
        $user->setFirstname('Aaa');
        $user->setLastname('Aaa');
        $user->setPhone('0768298272');
        $userManager->updateUser($user);
        $this->addReference('employeCantine', $user);

        // Creation du User employe TAP seul
        $user = $userManager->createUser();
        $user->setPlainPassword('aaa');
        $user->setEnabled(true);
        $user->setEmail('tap@email.com');
        $user->setRoles(array('ROLE_TAP'));
        $user->setFirstname('Aaa');
        $user->setLastname('Aaa');
        $user->setPhone('0768298272');
        $userManager->updateUser($user);
        $this->addReference('employeTap', $user);

        // Creation du User employe GARDERIE seul
        $user = $userManager->createUser();
        $user->setPlainPassword('aaa');
        $user->setEnabled(true);
        $user->setEmail('garderie@email.com');
        $user->setRoles(array('ROLE_GARDERIE'));
        $user->setFirstname('Aaa');
        $user->setLastname('Aaa');
        $user->setPhone('0768298272');
        $userManager->updateUser($user);
        $this->addReference('employeGarderie', $user);


        // Creation du User employe TAP/GARDERIE seul
        $user = $userManager->createUser();
        $user->setPlainPassword('aaa');
        $user->setEnabled(true);
        $user->setEmail('tapgarderie@email.com');
        $user->setRoles(array('ROLE_TAP', 'ROLE_GARDERIE'));
        $user->setFirstname('Aaa');
        $user->setLastname('Aaa');
        $user->setPhone('0768298272');
        $userManager->updateUser($user);
        $this->addReference('employeTapGarderie', $user);

    }

    /**
     * @return mixed
     */
    public function getSecurityManager()
    {
        return $this->container->get('security.encoder_factory');
    }

    /**
     * @param ContainerInterface|null $container
     */
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
