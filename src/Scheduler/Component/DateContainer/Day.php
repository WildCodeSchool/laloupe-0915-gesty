<?php
namespace Scheduler\Component\DateContainer;

class Day
{
    const WEEK_MONDAY       = '1';
    const WEEK_TUESDAY      = '2';
    const WEEK_WEDNESDAY    = '3';
    const WEEK_THURSDAY     = '4';
    const WEEK_FRIDAY       = '5';
    const WEEK_SATURDAY     = '6';
    const WEEK_SUNDAY       = '7';

    /**
     * Day constructor.
     * @param \DateTimeInterface $date
     */
    public function __construct(\DateTimeInterface $date)
    {
        $this->date         = new \DateTimeImmutable($date->format('Y-m-d H:i:s'));
        $this->year         = $date->format('Y');
        $this->month        = $date->format('m');
        $this->day          = $date->format('d');
        $this->weekDay      = self::getWeekDayFrom($date);
        $this->off          = false;
        $this->past         = false;
        $this->reserved     = false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getDateString();
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return string formatted 'Y-m-d'
     */
    public function getDateString()
    {
        return $this->year .'-'. $this->month .'-'. $this->day;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @return string
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return boolean
     */
    public function isOff()
    {
        return $this->off;
    }

    /**
     * @param boolean $isOff
     */
    public function setOff($isOff)
    {
        $this->off = $isOff;
    }

    /**
     * @return boolean
     */
    public function isPast()
    {
        return $this->past;
    }

    /**
     * @param boolean $isPast
     */
    public function setPast($isPast)
    {
        $this->past = $isPast;
    }

    /**
     * @return boolean
     */
    public function isReserved()
    {
        return $this->reserved;
    }

    /**
     * @param boolean $reserved
     */
    public function setReserved($reserved)
    {
        $this->reserved = $reserved;
    }

    /**
     * @param string $weekDay
     * @return bool
     */
    public function equalsWeekDay($weekDay)
    {
        return $weekDay === $this->weekDay;
    }

    /**
     * @param \DateTimeInterface $date
     * @return string Day::* constants
     */
    public static function getWeekDayFrom(\DateTimeInterface $date)
    {
        return str_replace('0', self::WEEK_SUNDAY, $date->format('w'));
    }

    /**
     * @var string
     */
    private $year;
    /**
     * @var string
     */
    private $month;
    /**
     * @var string
     */
    private $day;
    /**
     * @var string
     */
    private $weekDay;
    /**
     * @var bool
     */
    private $off;
    /**
     * @var bool
     */
    private $past;
    /**
     * @var bool
     */
    private $reserved;

    /**
     * @var \DateTimeImmutable
     */
    private $date;
};
