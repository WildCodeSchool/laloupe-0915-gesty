<?php
namespace WCS\CantineBundle\DataFixtures\ORM;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\Feries;


class LoadFerieData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $entity = new Feries();
        $entity->setAnnee('2016');
        $entity->setPaques(new \DateTime('2016-03-28'));
        $entity->setAscension(new \DateTime('2016-05-05'));
        $entity->setVendrediAscension(new \DateTime('2016-05-06'));
        $entity->setPentecote(new \DateTime('2015-05-16'));

        $manager->persist($entity);
        $this->setReference('feries_2016', $entity);

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 6; // ordre d'appel
    }
}