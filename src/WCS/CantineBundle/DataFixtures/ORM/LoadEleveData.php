<?php
// src/WCS/CantineBundle/DataFixtures/ORM/LoadUserData.php

namespace WCS\CantineBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\Eleve;


class LoadEleveData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $eleves = [
            ['Dupontel', 'Dupontel', 'Jean-Kevin', '2007-01-01', false, '', 'division-catteeu'],
            ['Dupontel', 'Dupontel', 'Kevina', '2008-08-15', false, '', 'division-catteeu'],
            ['Dupontel', 'Dupontel', 'Mathilde', '2009-08-15', false, '', 'division-catteeu'],
            ['Robert', 'Robert', 'Mickael', '2010-08-15', false, 'gluten', 'division-pichodo'],
            ['Robert', 'Robert', 'Franck', '2007-08-15', false, 'gluten', 'division-lucien'],
            ['Larissa', 'Larissa', 'Viviane', '2008-08-15', true, '', 'division-nouaille'],
            ['Larissa', 'Larissa', 'Gaelle', '2007-08-15', true, '', 'division-lucien'],
            ['Larissa', 'Larissa', 'Melina', '2010-08-15', true, '', 'division-pichodo'],
            ['Larissa', 'Larissa', 'Astrid', '2010-08-15', true, 'poivre', 'division-pichodo'],
            ['Veron', 'Veron', 'Matheos', '2010-08-15', false, '', 'division-pichodo'],
            ['Veron', 'Veron', 'Kevin', '2010-08-15', false, '', 'division-pichodo'],
            ['Bouteiller', 'Bouteiller', 'Thomas', '2007-08-15', false, '', 'division-lucien'],
            ['Bouteiller', 'Bouteiller', 'Isabelle', '2008-08-15', false, '', 'division-nouaille'],
            ['Butin', 'Butin', 'Arnold', '2008-08-15', false, '', 'division-nouaille'],
            ['Butin', 'Butin', 'Willy', '2010-08-15', false, '', 'division-pichodo'],
            ['Dorel', 'Dorel', 'Maelis', '2009-08-15', false, '', 'division-nouaille'],
            ['Batista', 'Batista', 'Jean', '2010-08-15', false, '', 'division-pichodo'],
            ['Nelon', 'Nelon', 'Aurianne', '2009-08-15', false, '', 'division-lemoue'],
            ['Nelon', 'Nelon', 'Enora', '2010-08-10', false, '', 'division-pichodo'],

        ];

        foreach ($eleves as $eleve) {
            $entity = new Eleve();
            $entity->setUser($this->getReference($eleve[0]));
            $entity->setNom($eleve[1]);
            $entity->setPrenom($eleve[2]);
            $entity->setDateDeNaissance(new \DateTime($eleve[3]));
            $entity->setRegimeSansPorc($eleve[4]);
            $entity->setAllergie($eleve[5]);
            $entity->setDivision($this->getReference($eleve[6]));
            $manager->persist($entity);
            $this->setReference($eleve[1]."-".$eleve[2], $entity);
        }

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
