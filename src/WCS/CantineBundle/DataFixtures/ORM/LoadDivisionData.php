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
        $entity = new Division();
        $entity->setGrade("CP-CE1");
        $entity->setHeadTeacher("Mme LEMOUE Laurence");
        $entity->setSchool($this->getReference("school-nddf"));
        $manager->persist($entity);
        $this->setReference('division-lemoue', $entity);

        $entity = new Division();
        $entity->setGrade("CE2-CM1");
        $entity->setHeadTeacher("Mme CATTEEU Anne-Sophie");
        $entity->setSchool($this->getReference("school-nddf"));
        $manager->persist($entity);
        $this->setReference('division-catteeu', $entity);

        $entity = new Division();
        $entity->setGrade("CE1");
        $entity->setHeadTeacher("Mle NOUAILLE-DEGORCE Valérie ");
        $entity->setSchool($this->getReference("school-rg"));
        $entity->addVoyage($this->getReference("voyage_versailles"));
        $entity->addVoyage($this->getReference("voyage_louvre"));
        $entity->addVoyage($this->getReference("voyage_malo"));
        $entity->addVoyage($this->getReference("voyage_disney"));
        $manager->persist($entity);
        $this->setReference('division-nouaille', $entity);

        $entity = new Division();
        $entity->setGrade("CE2");
        $entity->setHeadTeacher("Mme LUCIEN Nathalie ");
        $entity->setSchool($this->getReference("school-rg"));
        $entity->addVoyage($this->getReference("voyage_versailles"));
        $entity->addVoyage($this->getReference("voyage_maintenon"));
        $entity->addVoyage($this->getReference("voyage_padirac"));
        $manager->persist($entity);
        $this->setReference('division-lucien', $entity);

        $entity = new Division();
        $entity->setGrade("GS");
        $entity->setHeadTeacher("Mme PICHODO Marie-Pierre ");
        $entity->setSchool($this->getReference("school-ecureuils"));
        $manager->persist($entity);
        $this->setReference('division-pichodo', $entity);


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