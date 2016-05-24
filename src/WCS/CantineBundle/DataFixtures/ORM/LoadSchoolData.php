<?php
// src/WCS/CantineBundle/DataFixtures/ORM/LoadSchoolData.php

namespace WCS\CantineBundle\DataFixtures\ORM;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\School;


class LoadSchoolData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new School();
        $entity->setName("Les écureuils");
        $entity->setAdress("La Loupe");
        $entity->setActiveVoyage(false);
        $entity->setActiveGarderie(true);
        $entity->setActiveTap(true);
        $manager->persist($entity);
        $this->setReference('school-ecureuils', $entity);

        $entity = new School();
        $entity->setName("Notre Dame des Fleurs");
        $entity->setAdress("La Loupe");
        $entity->setActiveVoyage(true);
        $entity->setActiveGarderie(false);
        $entity->setActiveTap(false);
        $manager->persist($entity);
        $this->setReference('school-nddf', $entity);

        $entity = new School();
        $entity->setName("Roland-Garros");
        $entity->setAdress("La Loupe");
        $entity->setActiveVoyage(true);
        $entity->setActiveGarderie(false);
        $entity->setActiveTap(false);
        $manager->persist($entity);
        $this->setReference('school-rg', $entity);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 2; // ordre d'appel
    }
}