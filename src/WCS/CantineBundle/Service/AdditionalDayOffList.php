<?php
namespace WCS\CantineBundle\Service;

use WCS\CalendrierBundle\Service\DaysOffInterface;
use WCS\CantineBundle\Entity\ActivityType;

class AdditionalDayOffList implements DaysOffInterface
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $manager;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->manager = $em;
    }

    /**
     * @return array of \DateTime
     * @param array $options associative array with the followin keys
     *      - "activity_type" : ActivityType::const**
     *      - "eleve" : Entity Eleve
     */
    public function findDatesWithin(
        \WCS\CalendrierBundle\Service\Periode\Periode $periode,
        array $options = array()
    )
    {
        $daysOffArray = $this->manager->getRepository('WCSCantineBundle:Feries')->findListDatesWithin(
            $periode->getDebut(),
            $periode->getFin()
        );

        return $daysOffArray;
    }

    /**
     * @inheritdoc
     * @param array $options associative array with the followin keys
     *      - "activity_type" : ActivityType::const**
     *      - "eleve" : Entity Eleve
     */
    public function isOff(
        \DateTimeInterface $date,
        array $options = array()
    )
    {
        $isUsualActivityDayOff = ActivityType::isDayOff($options["activity_type"], $date);
        return $isUsualActivityDayOff;

        $daysOffArray = $this->manager->getRepository('WCSCantineBundle:Feries')->findListDatesWithin(
            $date,
            $date
        );
        $isDayOff = (in_array($date, $daysOffArray));
        if (true === $isDayOff) {
            return $isDayOff;
        }

    }
}
