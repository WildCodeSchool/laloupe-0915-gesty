<?php
namespace Scheduler\Component\DateContainer;


class WeekStats
{
    /**
     * @var \integer[]
     */
    private $total_per_days = array();

    /**
     * WeekStats constructor.
     */
    public function __construct(){
        $this->total_per_days = array_pad([], 7, 0);
    }

    /**
     * @return \integer
     */
    public function getTotalMonday()
    {
        return $this->total_per_days[Day::WEEK_MONDAY];
    }

    /**
     * @return \integer
     */
    public function getTotalTuesday()
    {
        return $this->total_per_days[Day::WEEK_TUESDAY];
    }

    /**
     * @return \integer
     */
    public function getTotalWednesday()
    {
        return $this->total_per_days[Day::WEEK_WEDNESDAY];
    }

    /**
     * @return \integer
     */
    public function getTotalThursday()
    {
        return $this->total_per_days[Day::WEEK_THURSDAY];
    }

    /**
     * @return \integer
     */
    public function getTotalFriday()
    {
        return $this->total_per_days[Day::WEEK_FRIDAY];
    }

    /**
     * @return \integer
     */
    public function getTotalSaturday()
    {
        return $this->total_per_days[Day::WEEK_SATURDAY];
    }

    /**
     * @return \integer
     */
    public function getTotalSunday()
    {
        return $this->total_per_days[Day::WEEK_SUNDAY];
    }

    /**
     * @param integer $dayOfWeek (Day::* constants)
     * @return \integer
     */
    public function getTotalForDay($dayOfWeek)
    {
        return $this->total_per_days[$dayOfWeek];
    }

    /**
     * @return \integer
     */
    public function getTotal()
    {
        return array_sum($this->total_per_days);
    }

    /**
     * @param integer $dayOfWeek (Day::* constants)
     * @param integer $total
     */
    public function setTotalDay($dayOfWeek, $total)
    {
        $this->total_per_days[$dayOfWeek] = $total;
    }
}
