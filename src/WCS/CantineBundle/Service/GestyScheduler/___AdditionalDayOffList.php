<?php
namespace WCS\CantineBundle\Service\GestyScheduler;

use Scheduler\Component\DateContainer\Period;

use \Doctrine\ORM\EntityManagerInterface;


class ____AdditionalDayOffList
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $em)
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
        Period $periode
    )
    {
        $daysOffArray = $this->manager->getRepository('WCSCantineBundle:Feries')->findListDatesWithin(
            $periode->getFirstDate(),
            $periode->getLastDate()
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


/*

        $daysOffArray = $this->manager->getRepository('WCSCantineBundle:Feries')->findListDatesWithin(
            $date,
            $date
        );
        $isDayOff = (in_array($date, $daysOffArray));
        if (true === $isDayOff) {
            return $isDayOff;
        }
*/
    }
}
