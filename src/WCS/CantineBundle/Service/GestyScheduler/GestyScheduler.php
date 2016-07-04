<?php
namespace WCS\CantineBundle\Service\GestyScheduler;

use Doctrine\Common\Persistence\ManagerRegistry;

use Scheduler\Component\DateContainer\Day;
use Scheduler\Component\DateContainer\Period;
use Scheduler\Component\Scheduler\Scheduler;
use Scheduler\Component\Scheduler\YearCalendar;



class GestyScheduler
{
    public function __construct(
        ManagerRegistry $managerRegistry
    )
    {
        $this->manager      = $managerRegistry->getManager();
        $this->scheduler    = new Scheduler();

        $this->init();
    }

    /**
     *
     */
    private function init()
    {
        /**
         * Add all usual days off during weeks
         */
        $allUsualDaysOff = ActivityType::getAllUsualDaysOffAsDayConst();
        foreach ($allUsualDaysOff as $activityConstType => $weekDay) {
            $this->scheduler->addWeekUsualDaysOff(
                $weekDay,
                array('index' => $activityConstType)
            );
        }

        /**
         * Add all school years
         * @var \WCS\CantineBundle\Entity\SchoolYear $schoolYear
         */
        $schoolYears = $this->manager->getRepository('WCSCantineBundle:SchoolYear')->findAll();
        foreach ($schoolYears as $schoolYear) {
            $period = new Period($schoolYear->getDateStart(), $schoolYear->getDateEnd());
            $this->scheduler->addYearPeriod($period);
        }

        /**
         * Add all school holidays
         * @var \WCS\CantineBundle\Entity\SchoolHoliday $holiday
         */
        $schoolYearHolidays = $this->manager->getRepository('WCSCantineBundle:SchoolHoliday')->findAll();

        foreach ($schoolYearHolidays as $holiday) {
            $dateStart = $holiday->getDateStart();

            $period = new Period($dateStart, $holiday->getDateEnd(), $holiday->getDescription());
            $this->scheduler->addPeriodDayOffs($period);
        }

        /**
         * Add all days off
         * @var \DateTime[] $datesDaysOff
         */

        $datesDaysOff = $this->manager->getRepository('WCSCantineBundle:Feries')->findListDatesWithin(
            $this->scheduler->getFirstYearPeriod()->getFirstDate(),
            $this->scheduler->getLastYearPeriod()->getLastDate()
        );

        $this->scheduler->addDatesDayOff($datesDaysOff);
        
    }

    /**
     * @param $activityTypeConstant
     * @param \DateTimeInterface $date
     * @return boolean
     */
    public function isDayOff(
        \DateTimeInterface $date,
        $activityTypeConstant
    )
    {
        return $this->scheduler->isDayOff(
            $date,
            array('index'=>$activityTypeConstant)
        );
    }

    /**
     * @param \DateTimeInterface $date
     * @return Period
     */
    public function getCurrentOrNextSchoolYear(
        \DateTimeInterface $date
    )
    {
      return $this->scheduler->getFirstAvailablePeriod(
            $this->scheduler->getYearPeriods(),
            $date
        );
    }

    /**
     * @param \DateTimeInterface $date
     * @return Period
     */
    public function getCurrentOrNextSchoolPeriod(
        \DateTimeInterface $date
    )
    {
        return $this->scheduler->getFirstAvailablePeriod(
            $this->scheduler->getAvailablePeriods(),
            $date
        );
    }

    /**
     * @param \DateTimeInterface $date
     * @param $activityTypeConstant
     * @return \DateTime
     */
    public function getFirstAvailableDate(
        \DateTimeInterface $date,
        $activityTypeConstant
    )
    {
        $firstAvailableDate = ActivityType::getFirstDayAvailable($activityTypeConstant, $date);

        return $this->scheduler->getFirstAvailableDate(
            $firstAvailableDate
        );
    }

    /**
     * @param \DateTimeInterface $date
     * @return YearCalendar
     */
    public function buildCanteenCalendar(
        \DateTimeInterface $date
    )
    {
        $scheduler = $this->scheduler;

        return new YearCalendar(
            $this->getCurrentOrNextSchoolYear( $date ),
            function(Day $d) use ($scheduler) {
                return $scheduler->isDayOff($d->getDate(), array('index'=>ActivityType::CANTEEN));
            }
        );
    }

    /**
     * @var Scheduler
     */
    private $scheduler;
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $manager;
}
