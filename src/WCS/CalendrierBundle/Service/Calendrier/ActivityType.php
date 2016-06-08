<?php
namespace WCS\CalendrierBundle\Service\Calendrier;


class ActivityType
{
    const CANTEEN           = 1;
    const TAP               = 2;
    const GARDERIE_MORNING  = 3;
    const GARDERIE_EVENING  = 4;

    private static $daysOfWeekOff = [
        self::CANTEEN               => [Day::WEEK_WEDNESDAY],
        self::TAP                   => [Day::WEEK_MONDAY, Day::WEEK_WEDNESDAY, Day::WEEK_FRIDAY],
        self::GARDERIE_MORNING      => [],
        self::GARDERIE_EVENING      => [Day::WEEK_WEDNESDAY]
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
}
