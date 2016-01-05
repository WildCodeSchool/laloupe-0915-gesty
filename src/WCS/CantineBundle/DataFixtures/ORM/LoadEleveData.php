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
        $entity1->setAtteste(true);
        $entity1->setCertifie(true);
        $entity1->setAutorise(true);
        $entity1->setNom('Robert');
        $entity1->setPrenom('Robert');
        $entity1->setDateDeNaissance(new \DateTime('2004-02-08'));
        $entity1->setRegimeSansPorc(false);
        $entity1->setDivision($this->getReference('division-lemoue'));
        $now = new \DateTime();
        $entity1->setDates($now->format('Y-m-d'));
        $manager->persist($entity1);

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