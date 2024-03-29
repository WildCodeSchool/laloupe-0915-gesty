<?php

namespace WCS\CantineBundle\Entity;
use Scheduler\Component\DateContainer\Period;
use Scheduler\Component\FileReader\Exception\InvalidFileException;
use Scheduler\Component\FileReader\ICalendarFileReader;

/**
 * SchoolHolidayRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SchoolHolidayRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param SchoolYear $schoolYear
     */
    public function removeAll(SchoolYear $schoolYear)
    {
        $query = $this->getEntityManager()->createQuery(
            'DELETE WCSCantineBundle:SchoolHoliday s WHERE s.schoolYear = :sy'
        )->setParameter(':sy', $schoolYear);

        $query->execute();
    }

    /**
     * @param SchoolYear $schoolYear
     */
    public function updateAllFrom(SchoolYear $schoolYear)
    {
        try {
            // load the file and add holidays
            $reader = new ICalendarFileReader( $schoolYear->getIcalendarPath() );

            $events = $reader->loadEvents();

            $this->removeAll($schoolYear);

            foreach($events as $event) {
                $this->addEventWithin($event, $schoolYear);
            }

            $this->getEntityManager()->flush();
        }
        catch(InvalidFileException $e) {
        }

    }

    /**
     * @param SchoolYear $schoolYear
     */
    private function addEventWithin(Period $event, SchoolYear $schoolYear)
    {
        if ($event->getFirstDate() > $schoolYear->getDateStart() &&
            $event->getLastDate() < $schoolYear->getDateEnd() ) {

            $holiday = new SchoolHoliday();
            $holiday->setDateStart($event->getFirstDate());
            $holiday->setDateEnd($event->getLastDate());
            $holiday->setDescription($event->getDescription());
            $holiday->setSchoolYear($schoolYear);
            $schoolYear->addSchoolHoliday($holiday);

            $this->getEntityManager()->persist($holiday);
        }
    }
}
