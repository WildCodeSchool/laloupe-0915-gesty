<?php
namespace WCS\EmployeeBundle\DataFixtures\ORM;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\Tap;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\School;


class LoadTapData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // ---------------------------------------------------------------
        // Eliott Valliant, GS, school Les Ecureuils
        // ---------------------------------------------------------------

        // Tuesdays of period between 18/04 and 05/07
        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-06-07'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-06-14'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-06-21'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-06-28'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);

        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-07-05'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-eliott'));
        $manager->persist($entity);


        // ---------------------------------------------------------------
        // Arabella Donatello, CE1, school Roland Garros
        // ---------------------------------------------------------------

        // Thursdays of period between 18/04 and 05/07
        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-06-09'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-06-16'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-06-23'));
        $entity->setStatus('0');
        $entity->setEleve($this->getReference('aaa-arabella'));
        $manager->persist($entity);

        $entity = new Tap();
        $entity->setDate(new \DateTime('2016-06-30'));
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
        return 8; // ordre d'appel
    }
}
