<?php
namespace WCS\CantineBundle\Service\GestyScheduler;

use Scheduler\Component\DateContainer\Day;
use Scheduler\Component\DateContainer\WeekStats;

class ActivityType
{
    const SCHOOL_USUALDAYOFF = 0;
    const CANTEEN            = 1;
    const TAP                = 2;
    const GARDERIE_MORNING   = 3;
    const GARDERIE_EVENING   = 4;
    const TRAVEL             = 5;

    private static $nbDaysUntilSubscribe = [
        'canteen'       => 8,
        'tap_garderie'  => 1,
        'travel'        => 1
    ];

    private static $daysOfWeekOff = [
        self::SCHOOL_USUALDAYOFF    => [Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::CANTEEN               => [Day::WEEK_WEDNESDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::TAP                   => [Day::WEEK_MONDAY, Day::WEEK_WEDNESDAY, Day::WEEK_FRIDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::GARDERIE_MORNING      => [Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::GARDERIE_EVENING      => [Day::WEEK_WEDNESDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::TRAVEL                => [Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
    ];

    private static $keyDates = [
        self::CANTEEN               => 'canteen',
        self::TAP                   => 'tap_garderie',
        self::GARDERIE_MORNING      => 'tap_garderie',
        self::GARDERIE_EVENING      => 'tap_garderie',
        self::TRAVEL                => 'travel'
    ];

    /**
     * @param $activityTypeConstant
     * @param \DateTimeInterface $dateDay
     * @return bool
     */

    public static function isDayOff($activityTypeConstant, \DateTimeInterface $dateDay)
    {
        $d = new Day($dateDay);

        $daysOff = self::$daysOfWeekOff[$activityTypeConstant];

        return \in_array($d->getWeekDay(), $daysOff);
    }


    /**
     * @param $activityTypeConstant
     * @return WeekStats
     */
    public static function getUsualDaysOff($activityTypeConstant)
    {
        $stats = new WeekStats();
        foreach (self::$daysOfWeekOff[$activityTypeConstant] as $dayOff) {
            $stats->setTotalDay($dayOff, 1);
        }
        return $stats;
    }


    /**
     * @return WeekStats[] association array with key as one of the constants and value a WeekStats
     * containing for each day a total of 1 or 0
     */
    public static function getAllUsualDaysOff()
    {
        $stats = [];
        foreach (self::$daysOfWeekOff as $type => $daysOff) {

            $stats[$type] = self::getUsualDaysOff($type);

        }
        return $stats;
    }

    /**
     * @return Day::const[][] association array with key as one of the constants and value
     * an array containing all week days off (Day::const)
     */
    public static function getAllUsualDaysOffAsDayConst()
    {
        return self::$daysOfWeekOff;
    }

    /**
     *
     * @return \DateTimeImmutable date qui suit la date courante + N jours
     */
    public static function getFirstDayAvailable($activityTypeConstant, \DateTimeInterface $date)
    {
        // récupère le nombre de jours à ajouter à la date disponible pour
        // retourner le 1er jour de disponible en fonction de l'activité
        $nbDays = 1;

        if (isset(self::$nbDaysUntilSubscribe[self::$keyDates[$activityTypeConstant]])) {
            $nbDays = self::$nbDaysUntilSubscribe[self::$keyDates[$activityTypeConstant]];

            // aucune inscription n'est possible le jour même
            if ('0'=== $nbDays) {
                throw new \LogicException('First day available must be >= 1 day.');
            }

            $nbDays = \intval($nbDays);
        }

        $dateFirstDayAvail = new \DateTimeImmutable(
            $date->format('Y-m-d').' +'.$nbDays.' day'
        );

        // tient compte des jours fermés pour l'activité donnée
        // recherche la première date qui n'est pas un jour fermé
        // et décale d'autant le 1er jour disponible
        $oneDay = new \DateInterval('P1D');
        while (self::isDayOff($activityTypeConstant, $dateFirstDayAvail)) {
            $dateFirstDayAvail = $dateFirstDayAvail->add($oneDay);
        }

        return $dateFirstDayAvail;
    }
}
