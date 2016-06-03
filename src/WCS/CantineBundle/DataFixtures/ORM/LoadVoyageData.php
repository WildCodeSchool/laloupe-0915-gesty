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
use WCS\CantineBundle\Entity\Voyage;



class LoadVoyageData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new Voyage();
        $entity->setLibelle("Visite du Chateau de Versailles");
        $entity->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-06-30 08:00:00") );
        $entity->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-06-30 17:00:00") );
        $entity->setEstAnnule(false);
        $manager->persist($entity);
        $this->setReference('voyage_versailles', $entity);

        $entity = new Voyage();
        $entity->setLibelle("Visite du Chateau de Maintenon");
        $entity->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-06-15 09:30:00") );
        $entity->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-06-16 18:00:00") );
        $entity->setEstAnnule(false);
        $manager->persist($entity);
        $this->setReference('voyage_maintenon', $entity);

        $entity = new Voyage();
        $entity->setLibelle("Visite du Louvre");
        $entity->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-06-29 09:30:00") );
        $entity->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-06-29 16:15:00") );
        $entity->setEstAnnule(true);
        $manager->persist($entity);
        $this->setReference('voyage_louvre', $entity);

        $entity = new Voyage();
        $entity->setLibelle("Visite du Goufre de Padirac");
        $entity->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2015-10-28 07:40:00") );
        $entity->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2015-11-07 19:25:30") );
        $entity->setEstAnnule(false);
        $manager->persist($entity);
        $this->setReference('voyage_padirac', $entity);

        $entity = new Voyage();
        $entity->setLibelle("Visite de Saint Malo");
        $entity->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2015-03-10 09:15:00") );
        $entity->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2015-03-12 20:00:00") );
        $entity->setEstAnnule(false);
        $manager->persist($entity);
        $this->setReference('voyage_malo', $entity);

        $entity = new Voyage();
        $entity->setLibelle("Disney");
        $entity->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2014-10-10 08:00:00") );
        $entity->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2014-10-10 18:00:00") );
        $entity->setEstAnnule(false);
        $manager->persist($entity);
        $this->setReference('voyage_disney', $entity);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 3; // ordre d'appel
    }
}