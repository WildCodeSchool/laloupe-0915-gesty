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
use WCS\CantineBundle\Entity\SchoolYear;



class LoadSchoolYearData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new SchoolYear();
        $entity->setDateStart(new \DateTime('2015-09-01'));
        $entity->setDateEnd(new \DateTime('2016-07-05'));
        $entity->setFilenameIcalendar('57722ef9de485.ics');
        $manager->persist($entity);

        $entity = new SchoolYear();
        $entity->setDateStart(new \DateTime('2016-09-01'));
        $entity->setDateEnd(new \DateTime('2017-07-07'));
        $entity->setFilenameIcalendar('57722ef9de485.ics');
        $manager->persist($entity);

        $entity = new SchoolYear();
        $entity->setDateStart(new \DateTime('2017-09-04'));
        $entity->setDateEnd(new \DateTime('2018-07-06'));
        $entity->setFilenameIcalendar('57722ef9de485.ics');
        $manager->persist($entity);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 16; // ordre d'appel
    }
}
