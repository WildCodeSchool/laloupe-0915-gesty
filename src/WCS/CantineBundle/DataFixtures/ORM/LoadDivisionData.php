<?php
// src/WCS/CantineBundle/DataFixtures/ORM/LoadDivisionData.php

namespace WCS\CantineBundle\DataFixtures\ORM;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\Division;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\School;


class LoadDivisionData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity1 = new Division();
        $entity1->setGrade("CP-CE1");
        $entity1->setHeadTeacher("Mme Lemoue");
        $entity1->setSchool($this->getReference("school-nddf"));
        $manager->persist($entity1);
        $this->setReference('division-lemoue', $entity1);

        $entity2 = new Division();
        $entity2->setGrade("CE2-CM1");
        $entity2->setHeadTeacher("Mme Catteeu");
        $entity2->setSchool($this->getReference("school-nddf"));
        $manager->persist($entity2);
        $this->setReference('division-catteeu', $entity2);

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