<?php
namespace WCS\EmployeeBundle\DataFixtures\ORM;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\Garderie;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\School;


class LoadGarderieData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // ---------------------------------------------------------------
        // Eliott Valliant, GS, school Les Ecureuils
        // ---------------------------------------------------------------

        // Mondays morning of period between 18/04 and 05/07
        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-06'));
        $entity->setEnableMorning(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-13'));
        $entity->setEnableMorning(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-20'));
        $entity->setEnableMorning(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-27'));
        $entity->setEnableMorning(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-07-04'));
        $entity->setEnableMorning(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);


        // Mondays nights of period between 18/04 and 05/07
        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-06'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-13'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-20'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-27'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-07-04'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);



        // Fridays nights of period between 18/04 and 05/07
        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-10'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-17'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-06-24'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDate(new \DateTime('2016-07-01'));
        $entity->setEnableEvening(true);
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);


        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 9; // ordre d'appel
    }
}
