<?php
namespace WCS\EmployeeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WCS\CantineBundle\Entity\Tap;


class LoadTapData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $inscs  = [
            ['2016-06-21', 'Dupontel-Jean-Kevin'],
            ['2016-06-21', 'Dupontel-Kevina'],
            ['2016-06-21', 'Dupontel-Mathilde'],
            ['2016-06-21', 'Robert-Mickael'],
            ['2016-06-21', 'Larissa-Viviane'],
            ['2016-06-21', 'Larissa-Gaelle'],
            ['2016-06-21', 'Larissa-Melina'],
            ['2016-06-21', 'Larissa-Astrid'],
            ['2016-06-21', 'Veron-Matheos'],
            ['2016-06-21', 'Veron-Kevin'],
            ['2016-06-21', 'Batista-Jean'],

            ['2016-06-23', 'Dupontel-Jean-Kevin'],
            ['2016-06-23', 'Dupontel-Kevina'],
            ['2016-06-23', 'Dupontel-Mathilde'],
            ['2016-06-23', 'Robert-Mickael'],
            ['2016-06-23', 'Larissa-Viviane'],
            ['2016-06-23', 'Larissa-Gaelle'],
            ['2016-06-23', 'Larissa-Melina'],
            ['2016-06-23', 'Larissa-Astrid'],
            ['2016-06-23', 'Veron-Matheos'],
            ['2016-06-23', 'Veron-Kevin'],
            ['2016-06-23', 'Batista-Jean'],

            ['2016-06-28', 'Dupontel-Jean-Kevin'],
            ['2016-06-28', 'Dupontel-Kevina'],
            ['2016-06-28', 'Dupontel-Mathilde'],
            ['2016-06-28', 'Robert-Mickael'],
            ['2016-06-28', 'Larissa-Viviane'],
            ['2016-06-28', 'Larissa-Gaelle'],
            ['2016-06-28', 'Larissa-Melina'],
            ['2016-06-28', 'Larissa-Astrid'],
            ['2016-06-28', 'Veron-Matheos'],
            ['2016-06-28', 'Veron-Kevin'],
            ['2016-06-28', 'Batista-Jean'],
        ];
        foreach ($inscs as $insc) {
            $entity = new Tap();
            $entity->setDate(new \DateTime($insc[0]));
            $entity->setStatus('0');
            $entity->setEleve($this->getReference($insc[1]));
            $manager->persist($entity);
        }

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
