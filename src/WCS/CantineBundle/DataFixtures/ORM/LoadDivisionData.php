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
        $entity1->setHeadTeacher("Mme LEMOUE Laurence");
        $entity1->setSchool($this->getReference("school-nddf"));
        $manager->persist($entity1);
        $this->setReference('division-lemoue', $entity1);

        $entity2 = new Division();
        $entity2->setGrade("CE2-CM1");
        $entity2->setHeadTeacher("Mme CATTEEU Anne-Sophie");
        $entity2->setSchool($this->getReference("school-nddf"));
        $manager->persist($entity2);
        $this->setReference('division-catteeu', $entity2);

        $entity3 = new Division();
        $entity3->setGrade("CE1");
        $entity3->setHeadTeacher("Mle NOUAILLE-DEGORCE Valérie ");
        $entity3->setSchool($this->getReference("school-rg"));
        $manager->persist($entity3);
        $this->setReference('division-nouaille', $entity3);

        $entity4 = new Division();
        $entity4->setGrade("CE2");
        $entity4->setHeadTeacher("Mme LUCIEN Nathalie ");
        $entity4->setSchool($this->getReference("school-rg"));
        $manager->persist($entity4);
        $this->setReference('division-lucien', $entity4);

        $entity5 = new Division();
        $entity5->setGrade("GS");
        $entity5->setHeadTeacher("Mme PICHODO Marie-Pierre ");
        $entity5->setSchool($this->getReference("school-ecureuils"));
        $manager->persist($entity5);
        $this->setReference('division-pichodo', $entity5);


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