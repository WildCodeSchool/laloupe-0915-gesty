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
        $entity1 = new Eleve();
        $entity1->setUser($this->getReference('user'));
        $entity1->setNom('Robert');
        $entity1->setPrenom('Robert');
        $entity1->setDateDeNaissance(new \DateTime('2004-02-08'));
        $entity1->setRegimeSansPorc(false);
        $entity1->setDivision($this->getReference('division-lemoue'));
        $manager->persist($entity1);

        $entity2 = new Eleve();
        $entity2->setUser($this->getReference('user'));
        $entity2->setNom('Donatello');
        $entity2->setPrenom('Arabella');
        $entity2->setDateDeNaissance(new \DateTime('2006-06-15'));
        $entity2->setRegimeSansPorc(false);
        $entity2->setDivision($this->getReference('division-catteeu'));
        $manager->persist($entity2);

        $entity3 = new Eleve();
        $entity3->setUser($this->getReference('user'));
        $entity3->setNom('Sylvestre');
        $entity3->setPrenom('Coralie');
        $entity3->setDateDeNaissance(new \DateTime('2011-09-21'));
        $entity3->setRegimeSansPorc(false);
        $entity3->setDivision($this->getReference('division-nouaille'));
        $manager->persist($entity3);

        $entity4 = new Eleve();
        $entity4->setUser($this->getReference('user'));
        $entity4->setNom('Vaillant');
        $entity4->setPrenom('Eliott');
        $entity4->setDateDeNaissance(new \DateTime('2010-07-28'));
        $entity4->setRegimeSansPorc(false);
        $entity4->setDivision($this->getReference('division-pichodo'));
        $manager->persist($entity4);

        $entity5 = new Eleve();
        $entity5->setUser($this->getReference('user'));
        $entity5->setNom('Truite');
        $entity5->setPrenom('Marine');
        $entity5->setDateDeNaissance(new \DateTime('2009-10-18'));
        $entity5->setRegimeSansPorc(false);
        $entity5->setDivision($this->getReference('division-pichodo'));
        $manager->persist($entity5);


        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 4; // ordre d'appel
    }
}