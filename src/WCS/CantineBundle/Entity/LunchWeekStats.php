<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 06/06/16
 * Time: 12:11
 */

namespace WCS\CantineBundle\Entity;
use WCS\CalendrierBundle\Service\Calendrier\Day;

class LunchWeekStats
{
    /**
     * @var \integer[]
     */
    private $total_per_days = array();

    public function __construct(){
        $this->total_per_days = array_pad([], 4, 0);
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
