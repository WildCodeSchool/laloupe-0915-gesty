<?php
/**
 * We choose to implement our own Period class whereas we could use \DatePeriod.
 * Reasons :
 * - PHP provide \DatePeriod which could be fined, but the required \DateInterval
 *   in the constructor makes the class not perfectly adapted to the needs here.
 *
 * - The end date is not included in the period, while we need this date to be
 *   included in the range.
 *
 * - we need a free iterator in which we can choose the interval,
 *   and not a fixed one at the instanciation of the period.
 *
 * @see http://php.net/manual/fr/class.dateperiod.php
 *
 */
namespace SchoolCalendar\Component\Scheduler;


/**
 * This class enables to setup a period of dates (both included)
 * and enable to get any DateIterator with free DateInterval.
 * Plus, it enables to add a description for the period.
 */
class Period implements PeriodInterface
{
    /*--------------------------------------------------------------------------------
        PUBLIC METHODS
    --------------------------------------------------------------------------------*/

    /**
     * @inheritdoc
     */
    public function __construct(
        \DateTimeInterface $firstDate,
        \DateTimeInterface $lastDate,
        $description=''
    )
    {
        $this->firstDate    = new \DateTimeImmutable($firstDate->format('Y-m-d H:i:s'));
        $this->lastDate     = new \DateTimeImmutable($lastDate->format('Y-m-d H:i:s'));
        $this->description  = $description;

        if ($this->firstDate > $this->lastDate) {
            throw new \LogicException('The first date must be lower than the last date');
        }
    }


    /**
     * @inheritdoc
     */
    public function getFirstDate()
    {
        return $this->firstDate;
    }

    /**
     * @inheritdoc
     */
    public function getLastDate()
    {
        return $this->lastDate;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function isIncluded(\DateTimeInterface $date)
    {
        return  $date >= $this->firstDate && $date <= $this->lastDate;
    }

    /**
     * @return PeriodIterator iterator that enable to get every days in the period.
     */
    public function getDayIterator()
    {
        return new PeriodIterator($this, new \DateInterval('P1D'));
    }

    /**
     * @return PeriodIterator iterator that enable to get every months in the period.
     */
    public function getMonthIterator()
    {
        return new PeriodIterator($this, new \DateInterval('P1M'));
    }

    /**
     * @inheritdoc
     */
    public function getIterator(\DateInterval $increment)
    {
        return new PeriodIterator($this, $increment);
    }


    /*--------------------------------------------------------------------------------
      ATTRIBUTES
    --------------------------------------------------------------------------------*/

    /**
     * @var \DateTimeImmutable
     */
    private $firstDate;

    /**
     * @var \DateTimeImmutable
     */
    private $lastDate;

    /**
     * @var string an optional description
     */
    private $description;

}
