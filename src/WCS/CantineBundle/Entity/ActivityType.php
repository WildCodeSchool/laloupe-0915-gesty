<?php
//namespace WCS\CalendrierBundle\Service\Calendrier;
namespace WCS\CantineBundle\Entity;

use WCS\CalendrierBundle\Service\Calendrier\Day;

class ActivityType
{
    const CANTEEN           = 1;
    const TAP               = 2;
    const GARDERIE_MORNING  = 3;
    const GARDERIE_EVENING  = 4;

    private static $daysOfWeekOff = [
        self::CANTEEN               => [Day::WEEK_WEDNESDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::TAP                   => [Day::WEEK_MONDAY, Day::WEEK_WEDNESDAY, Day::WEEK_FRIDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::GARDERIE_MORNING      => [Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::GARDERIE_EVENING      => [Day::WEEK_WEDNESDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY]
    ];

    /**
     * Return the list of the usual days off in a week
     *
     * @param
     *      CANTEEN|TAP|GARDERIE_MORNING|GARDERIE_EVENING
     *      $activityTypeConstant
     * @return array of
     */
    public static function getDaysOfWeekOff($activityTypeConstant)
    {
        return self::$daysOfWeekOff[$activityTypeConstant];
    }

    public static function isDayOff($activityTypeConstant, \DateTimeInterface $dateDay)
    {
        $d = new Day($dateDay);

        $daysOff = self::getDaysOfWeekOff($activityTypeConstant);

        return \in_array($d->getDayOfWeek(), $daysOff);
    }

}
