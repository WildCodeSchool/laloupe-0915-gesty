<?php
namespace SchoolCalendar\Component\Scheduler;


interface PeriodInterface
{
    /**
     * Periode constructor.
     *
     * Create a fixed period with an optional description.
     *
     * @param \DateTimeInterface    $firstDate
     * @param \DateTimeInterface    $lastDate
     * @param string                $description
     *                              Optional, description of the period
     *
     * @throws \LogicException
     */
    function __construct(
        \DateTimeInterface $firstDate,
        \DateTimeInterface $lastDate,
        $description=''
    );

    /**
     * @return \DateTimeImmutable the first date of the period
     */
    function getFirstDate();

    /**
     * @return \DateTimeImmutable the last date of the period
     */
    function getLastDate();

    /**
     * @return string the description
     */
    function getDescription();

    /**
     * @param \DateTimeInterface $date
     * @return boolean true if the date is inside the period (or equals to the first or last date)
     */
    function isIncluded(\DateTimeInterface $date);

    /**
     * @param \DateInterval $increment the interval of dates which will be used in the iterator.
     * @return PeriodIterator custom iterator that enable to get every increment in the period.
     *
     * @see http://php.net/manual/fr/class.dateinterval.php
     */
    function getIterator(\DateInterval $increment);
}
