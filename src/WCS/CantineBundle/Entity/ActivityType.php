<?php
//namespace WCS\CalendrierBundle\Service\Calendrier;
namespace WCS\CantineBundle\Entity;

use WCS\CalendrierBundle\Service\Calendrier\Day;
use WCS\CalendrierBundle\Service\DateNow;
use WCS\CalendrierBundle\Utils\WeekStats;

class ActivityType
{
    const CANTEEN           = 1;
    const TAP               = 2;
    const GARDERIE_MORNING  = 3;
    const GARDERIE_EVENING  = 4;
    const TRAVEL            = 5;

    private static $daysOfWeekOff = [
        self::CANTEEN               => [Day::WEEK_WEDNESDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::TAP                   => [Day::WEEK_MONDAY, Day::WEEK_WEDNESDAY, Day::WEEK_FRIDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::GARDERIE_MORNING      => [Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::GARDERIE_EVENING      => [Day::WEEK_WEDNESDAY, Day::WEEK_SATURDAY, Day::WEEK_SUNDAY],
        self::TRAVEL                => [Day::WEEK_SATURDAY, Day::WEEK_SUNDAY]
    ];

    private static $keyDates = [
        self::CANTEEN               => 'canteen',
        self::TAP                   => 'tap_garderie',
        self::GARDERIE_MORNING      => 'tap_garderie',
        self::GARDERIE_EVENING      => 'tap_garderie',
        self::TRAVEL                => 'travel'
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

    /**
     * @param $activityTypeConstant
     * @param \DateTimeInterface $dateDay
     * @return bool
     */
    public static function isDayOff($activityTypeConstant, \DateTimeInterface $dateDay)
    {
        $d = new Day($dateDay);

        $daysOff = self::getDaysOfWeekOff($activityTypeConstant);

        return \in_array($d->getDayOfWeek(), $daysOff);
    }

    /**
     * @param $activityTypeConstant
     * @return mixed
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
     * @return WeekStats[] association arrat with key as one of the constants and value a WeekStats
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
     *
     * @return \DateTimeImmutable date qui suit la date courante + N jours
     */
    public static function getFirstDayAvailable($activityTypeConstant, DateNow $dateNow)
    {
        // considère avant tout que la date du jour est la première date disponible
        $dateFirstDayAvail = $dateNow->getDate();

        // récupère le nombre de jours à ajouter à la date disponible pour
        // retourner le 1er jour de disponible en fonction de l'activité
        $avail_start = $dateNow->getOptions('available_start');

        if (isset($avail_start[self::$keyDates[$activityTypeConstant]])) {
            $nbDays = $avail_start[self::$keyDates[$activityTypeConstant]];

            // aucune inscription n'est possible le jour même
            if ('0'=== $nbDays) {
                throw new \LogicException('First day available must be >= 1 day.');
            }

            $dateFirstDayAvail = new \DateTimeImmutable(
                $dateFirstDayAvail->format('Y-m-d').' +'.$nbDays.' day'
            );
        }

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
