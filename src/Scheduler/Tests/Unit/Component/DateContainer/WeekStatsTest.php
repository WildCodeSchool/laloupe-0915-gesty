<?php
namespace Scheduler\Tests\Unit\Component\Scheduler;

use Scheduler\Component\DateContainer\WeekStats;
use Scheduler\Component\DateContainer\Day;

/**
 * Class WeekStatsTest
 * @coversDefaultClass \Scheduler\Component\DateContainer\WeekStats
 */
class WeekStatsTest extends \PHPUnit_Framework_TestCase
{
    public function provideTotals()
    {
        return [
            [Day::WEEK_MONDAY],
            [Day::WEEK_TUESDAY],
            [Day::WEEK_WEDNESDAY],
            [Day::WEEK_THURSDAY],
            [Day::WEEK_FRIDAY],
            [Day::WEEK_SATURDAY],
            [Day::WEEK_SUNDAY]
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotalForDay
     *
     * @dataProvider provideTotals
     *
     * @param integer $weekDay (Day::const*)
     */
    public function testTotalForOneDay($weekDay)
    {
        $stats = new WeekStats();

        $stats->setTotalDay($weekDay, 0);
        $this->assertEquals(0, $stats->getTotalForDay($weekDay), "Get Total for a day must return zero");

        $stats->setTotalDay($weekDay, 10);
        $this->assertEquals(10, $stats->getTotalForDay($weekDay), "Get Total for a day must return ten");
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotal
     */
    public function testTotalForTheWeeks()
    {
        $stats = new WeekStats();

        $expectTotal = 1+2+3+4+5+6+7;

        $stats->setTotalDay(Day::WEEK_MONDAY, 1);
        $stats->setTotalDay(Day::WEEK_TUESDAY, 2);
        $stats->setTotalDay(Day::WEEK_WEDNESDAY, 3);
        $stats->setTotalDay(Day::WEEK_THURSDAY, 4);
        $stats->setTotalDay(Day::WEEK_FRIDAY, 5);
        $stats->setTotalDay(Day::WEEK_SATURDAY, 6);
        $stats->setTotalDay(Day::WEEK_SUNDAY, 7);

        $this->assertEquals($expectTotal, $stats->getTotal());
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotalMonday
     */
    public function testEnsureHelperTotalMondayCorrectlyReturned()
    {
        $stats = new WeekStats();
        $stats->setTotalDay(Day::WEEK_MONDAY, 5);

        $this->assertEquals(5, $stats->getTotalMonday());
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotalTuesday
     */
    public function testEnsureHelperTotalTuesdayCorrectlyReturned()
    {
        $stats = new WeekStats();
        $stats->setTotalDay(Day::WEEK_TUESDAY, 5);

        $this->assertEquals(5, $stats->getTotalTuesday());
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotalWednesday
     */
    public function testEnsureHelperTotalWednesdayCorrectlyReturned()
    {
        $stats = new WeekStats();
        $stats->setTotalDay(Day::WEEK_WEDNESDAY, 5);

        $this->assertEquals(5, $stats->getTotalWednesday());
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotalThursday
     */
    public function testEnsureHelperTotalThursdayCorrectlyReturned()
    {
        $stats = new WeekStats();
        $stats->setTotalDay(Day::WEEK_THURSDAY, 5);

        $this->assertEquals(5, $stats->getTotalThursday());
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotalFriday
     */
    public function testEnsureHelperTotalFridayCorrectlyReturned()
    {
        $stats = new WeekStats();
        $stats->setTotalDay(Day::WEEK_FRIDAY, 5);

        $this->assertEquals(5, $stats->getTotalFriday());
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotalSaturday
     */
    public function testEnsureHelperTotalSaturdayCorrectlyReturned()
    {
        $stats = new WeekStats();
        $stats->setTotalDay(Day::WEEK_SATURDAY, 5);

        $this->assertEquals(5, $stats->getTotalSaturday());
    }

    /**
     * @covers ::__construct
     * @covers ::setTotalDay
     * @covers ::getTotalSunday
     */
    public function testEnsureHelperTotalSundayCorrectlyReturned()
    {
        $stats = new WeekStats();
        $stats->setTotalDay(Day::WEEK_SUNDAY, 5);

        $this->assertEquals(5, $stats->getTotalSunday());
    }
}
