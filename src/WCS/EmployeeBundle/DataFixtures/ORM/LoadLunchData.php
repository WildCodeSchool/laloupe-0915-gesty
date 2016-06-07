<?php
namespace WCS\EmployeeBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\Lunch;


class LoadLunchData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // ---------------------------------------------------------------
        // Robert Robert, CP-CE1 school Notre Dame des Fleurs
        // without pork diet
        // ---------------------------------------------------------------

        // Mondays of june
        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-06'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-robert'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-13'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-robert'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-20'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-robert'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-27'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-robert'));
        $manager->persist($entity);

        // Tuesdays of june
        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-07'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-robert'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-14'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-robert'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-21'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-robert'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-28'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-robert'));
        $manager->persist($entity);


        // ---------------------------------------------------------------
        // Arabella Donatello, CE1, school Roland Garros
        // traditional diet
        // ---------------------------------------------------------------

        // Mondays of june
        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-06'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-13'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-20'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-27'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        // Tuesdays of june
        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-07'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-14'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-21'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-28'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);


        // 1 Thursday the 1st week and 1 Friday the 2nd week of june
        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-09'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Lunch();
        $entity->setDate(new \DateTime('2016-06-17'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);


        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 7; // ordre d'appel
    }
}