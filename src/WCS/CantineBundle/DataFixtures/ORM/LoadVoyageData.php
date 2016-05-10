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
        $entity1 = new Voyage();
        $entity1->setLibelle("Visite du chateau de Versailles");
        $entity1->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-05-30 08:00:00") );
        $entity1->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-05-31 17:00:00") );
        $entity1->setEstAnnule(false);
        $manager->persist($entity1);
        $this->setReference('voyage_versailles', $entity1);

        $entity2 = new Voyage();
        $entity2->setLibelle("Visite du Louvre");
        $entity2->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-06-29 09:30:00") );
        $entity2->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2016-06-29 16:15:00") );
        $entity2->setEstAnnule(true);
        $manager->persist($entity2);
        $this->setReference('voyage_louvre', $entity2);

        $entity3 = new Voyage();
        $entity3->setLibelle("Visite du Goufre de Padirac");
        $entity3->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2015-10-28 07:40:00") );
        $entity3->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2015-11-07 19:25:30") );
        $entity3->setEstAnnule(false);
        $manager->persist($entity3);
        $this->setReference('voyage_padirac', $entity3);

        $entity4 = new Voyage();
        $entity4->setLibelle("Visite de Saint Malo");
        $entity4->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2015-03-10 05:12:00") );
        $entity4->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2015-03-12 22:23:00") );
        $entity4->setEstAnnule(false);
        $manager->persist($entity4);
        $this->setReference('voyage_malo', $entity4);

        $entity5 = new Voyage();
        $entity5->setLibelle("Disney");
        $entity5->setDateDebut( \DateTime::createFromFormat("Y-m-d H:i:s" , "2014-10-10 00:00:00") );
        $entity5->setDateFin( \DateTime::createFromFormat("Y-m-d H:i:s" , "2014-10-10 00:00:00") );
        $entity5->setEstAnnule(false);
        $manager->persist($entity5);
        $this->setReference('voyage_disney', $entity5);



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