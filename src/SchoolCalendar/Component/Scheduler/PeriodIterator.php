<?php
namespace SchoolCalendar\Component\Scheduler;

/**
 * Class PeriodIterator
 * @package WCS\SchoolCalendarBundle\Period\Iterator
 */
class PeriodIterator implements \Iterator, \Countable
{
    /**
     * @param PeriodInterface $period
     * @param \DateInterval $increment
     */
    public function __construct(
        PeriodInterface $period,
        \DateInterval $increment
    )
    {
        $this->period       = $period;
        $this->increment    = $increment;
        $this->current      = $this->period->getFirstDate();
        $this->lastDateStr  = $period->getLastDate()->format('Y-m-d');
        $this->index        = 0;
        $this->isValid      = true;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function current()
    {
        if ($this->isValid) {
            return $this->current;
        }
        return $this->period->getLastDate();
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Step to the next date
     */
    public function next()
    {
        $this->current = $this->current->add($this->increment);
        $this->index++;
    }

    /**
     * reset to the first date
     */
    public function rewind()
    {
        $this->current = $this->period->getFirstDate();
        $this->index = 0;
        $this->isValid = true;
    }

    /**
     * @return bool True if the iterator has not reach the last date (included)
     */
    public function valid()
    {
        $this->isValid = $this->current->format('Y-m-d') <= $this->lastDateStr;
        return $this->isValid;
    }

    /**
     * @return int the number of items indexed by the iterator
     */
    public function count()
    {
        $nbItems = 0;
        $current = $this->period->getFirstDate();
        $end = $this->period->getLastDate();
        while ($current <= $end) {
            $nbItems++;
            $current = $current->add($this->increment);
        }
        return $nbItems;
    }

    /**
     * @var string end period date format 'Y-m-d
     */
    private $lastDateStr;

    /**
     * @var PeriodInterface
     */
    private $period;

    /**
     * @var \DateTimeImmutable
     */
    private $current;

    /**
     * @var \DateInterval
     */
    private $increment;

    /**
     * @var integer
     */
    private $index;

    /**
     * @var bool
     */
    private $isValid;
}
