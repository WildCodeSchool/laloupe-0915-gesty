<?php
namespace WCS\CalendrierBundle\Utils;


use WCS\CalendrierBundle\Service\Periode\PeriodeInterface;

class DatesPeriodIterator implements \Iterator
{
    public function __construct(PeriodeInterface $periode, \DateInterval $increment)
    {
        $this->extPeriode   = $periode;
        $this->current      = $periode->getDebut();
        $this->increment    = $increment;
        $this->lastDateStr  = $periode->getFin()->format('Y-m-d');
        $this->rewind();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * increment the date
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
        $this->current = $this->extPeriode->getDebut();
        $this->index = 0;
    }

    /**
     * @return bool True if the iterator has not reach the last date (included)
     */
    public function valid()
    {
        return $this->current->format('Y-m-d') <= $this->lastDateStr;
    }

    /**
     * @var integer
     */
    private $index;

    /**
     * @var Periode
     */
    private $extPeriode;
    /**
     * @var \DateTimeImmutable
     */
    private $current;
    /**
     * @var \DateInterval
     */
    private $increment;

    /**
     * @var string date 'Y-m-d"
     */
    private $lastDateStr;
}
