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
        $entity->setDateHeure(new \DateTime('2016-06-06 08:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-13 08:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-20 08:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-27 08:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-07-04 08:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);


        // Mondays nights of period between 18/04 and 05/07
        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-06 17:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-13 17:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-20 17:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-27 17:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-07-04 17:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);



        // Fridays nights of period between 18/04 and 05/07
        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-10 17:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-17 17:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-06-24 17:00'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Garderie();
        $entity->setDateHeure(new \DateTime('2016-07-01 17:00'));
        $entity->setStatus('0');
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
