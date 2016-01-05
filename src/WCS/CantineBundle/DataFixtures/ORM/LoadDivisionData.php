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
        $entity1 = new School();
        $entity1->setName("Les écureuils");
        $entity1->setAdress("La Loupe");
        $manager->persist($entity1);

        $entity2 = new School();
        $entity2->setName("Notre Dame des Fleurs");
        $entity2->setAdress("La Loupe");
        $manager->persist($entity2);

        $entity3 = new School();
        $entity3->setName("Roland-Garros");
        $entity3->setAdress("La Loupe");
        $manager->persist($entity3);

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