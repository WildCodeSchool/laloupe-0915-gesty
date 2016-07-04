<?php
// src/WCS/CantineBundle/DataFixtures/ORM/LoadSchoolData.php

namespace WCS\CantineBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\SchoolYear;



class LoadSchoolYearData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        // record all school years
        $entity = new SchoolYear();
        $entity->setDateStart(new \DateTime('2015-09-01'));
        $entity->setDateEnd(new \DateTime('2016-07-05'));
        $entity->setFilenameIcalendar('Calendrier_Scolaire_Zone_B.ics');
        $manager->persist($entity);
        $this->setReference('2015-2016', $entity);

        $entity = new SchoolYear();
        $entity->setDateStart(new \DateTime('2016-09-01'));
        $entity->setDateEnd(new \DateTime('2017-07-07'));
        $entity->setFilenameIcalendar('Calendrier_Scolaire_Zone_B.ics');
        $manager->persist($entity);
        $this->setReference('2016-2017', $entity);

        $entity = new SchoolYear();
        $entity->setDateStart(new \DateTime('2017-09-04'));
        $entity->setDateEnd(new \DateTime('2018-07-06'));
        $entity->setFilenameIcalendar('Calendrier_Scolaire_Zone_B.ics');
        $manager->persist($entity);
        $this->setReference('2017-2018', $entity);

        $manager->flush();


        $path = __DIR__.'/../Files/';

        // record all holidays
        $this->getReference('2015-2016')->setUploadAbsolutePath($path);
        $manager->getRepository('WCSCantineBundle:SchoolHoliday')->updateAllFrom(
            $this->getReference('2015-2016')
        );
        $this->getReference('2016-2017')->setUploadAbsolutePath($path);
        $manager->getRepository('WCSCantineBundle:SchoolHoliday')->updateAllFrom(
            $this->getReference('2016-2017')
        );
        $this->getReference('2017-2018')->setUploadAbsolutePath($path);
        $manager->getRepository('WCSCantineBundle:SchoolHoliday')->updateAllFrom(
            $this->getReference('2017-2018')
        );

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
