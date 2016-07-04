<?php
// src/WCS/CantineBundle/DataFixtures/ORM/LoadSchoolData.php

namespace WCS\CantineBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\School;


class LoadSchoolData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $schools  = [
            ['Les écureuils',           'La Loupe', true, true,  true,  false, 'school-ecureuils'],
            ['Notre Dame des Fleurs',   'La Loupe', true, false, false, false, 'school-nddf'],
            ['Roland-Garros',           'La Loupe', true, true,  true,  true,  'school-rg']
        ];
        foreach ($schools as $school) {

            $entity = new School();
            $entity->setName($school[0]);
            $entity->setAdress($school[1]);
            $entity->setActiveCantine($school[2]);
            $entity->setActiveGarderie($school[3]);
            $entity->setActiveTap($school[4]);
            $entity->setActiveVoyage($school[5]);
            $manager->persist($entity);
            $this->setReference($school[6], $entity);
        }

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
