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
use WCS\CantineBundle\Entity\Eleve;


class LoadEleveData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new Eleve();
        $entity->setUser($this->getReference('user'));
        $entity->setNom('Robert');
        $entity->setPrenom('Robert');
        $entity->setDateDeNaissance(new \DateTime('2004-02-08'));
        $entity->setRegimeSansPorc(false);
        $entity->setDivision($this->getReference('division-lemoue'));
        $manager->persist($entity);

        $entity = new Eleve();
        $entity->setUser($this->getReference('user'));
        $entity->setNom('Donatello');
        $entity->setPrenom('Arabella');
        $entity->setDateDeNaissance(new \DateTime('2006-06-15'));
        $entity->setRegimeSansPorc(false);
        $entity->setDivision($this->getReference('division-nouaille'));
        $entity->addVoyage($this->getReference("voyage_louvre"));
        $manager->persist($entity);

        $entity = new Eleve();
        $entity->setUser($this->getReference('user'));
        $entity->setNom('Sylvestre');
        $entity->setPrenom('Coralie');
        $entity->setDateDeNaissance(new \DateTime('2011-09-21'));
        $entity->setRegimeSansPorc(false);
        $entity->setDivision($this->getReference('division-lucien'));
        $entity->addVoyage($this->getReference("voyage_versailles"));
        $entity->addVoyage($this->getReference("voyage_maintenon"));
        $manager->persist($entity);

        $entity = new Eleve();
        $entity->setUser($this->getReference('user'));
        $entity->setNom('Vaillant');
        $entity->setPrenom('Eliott');
        $entity->setDateDeNaissance(new \DateTime('2010-07-28'));
        $entity->setRegimeSansPorc(false);
        $entity->setDivision($this->getReference('division-pichodo'));
        $manager->persist($entity);



        $entity = new Eleve();
        $entity->setUser($this->getReference('user3'));
        $entity->setNom('Truite');
        $entity->setPrenom('Marine');
        $entity->setDateDeNaissance(new \DateTime('2009-10-18'));
        $entity->setRegimeSansPorc(false);
        $entity->setDivision($this->getReference('division-catteeu'));
        $manager->persist($entity);


        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 5; // ordre d'appel
    }
}