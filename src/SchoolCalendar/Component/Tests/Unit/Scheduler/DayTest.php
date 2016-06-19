<?php
namespace SchoolCalendar\Component\Tests\Unit\Scheduler;

use SchoolCalendar\Component\Scheduler\Day;

/**
 * Class DayTest
 * @covers \SchoolCalendar\Component\Scheduler\Day
 * @coversDefaultClass \SchoolCalendar\Component\Scheduler\Day
 */
class DayTest extends \PHPUnit_Framework_TestCase
{
    public function provideValidDates()
    {
        return [
            [ new Day(new \DateTime('2016-01-01 00:00:00')), '2016-01-01 00:00:00' ],
            [ new Day(new \DateTime('2017-12-31 17:30:15')), '2017-12-31 17:30:15' ],
        ];
    }

    public function provideValidYMDDates()
    {
        return [
            [ new Day(new \DateTime('2016-01-01 17:30:15')), '2016-01-01' ],
            [ new Day(new \DateTime('2017-12-31 17:30:15')), '2017-12-31' ],
        ];
    }

    public function provideValidYears()
    {
        return [
            [ new Day(new \DateTime('2016-01-01')), '2016' ],
            [ new Day(new \DateTime('2017-12-31')), '2017' ],
        ];
    }

    public function provideValidMonths()
    {
        return [
            [ new Day(new \DateTime('2015-01-09')), '01' ],
            [ new Day(new \DateTime('2016-12-09')), '12' ],
        ];
    }

    public function provideValidDays()
    {
        return [
            [ new Day(new \DateTime('2016-09-01')), '01' ],
            [ new Day(new \DateTime('2016-09-12')), '12' ],
            [ new Day(new \DateTime('2016-09-30')), '30' ],
        ];
    }

    public function provideValidWeekDays()
    {
        return [
            [ new Day(new \DateTime('2016-05-30')), '1' ],
            [ new Day(new \DateTime('2016-05-31')), '2' ],
            [ new Day(new \DateTime('2016-06-01')), '3' ],
            [ new Day(new \DateTime('2016-06-02')), '4' ],
            [ new Day(new \DateTime('2016-06-03')), '5' ],
            [ new Day(new \DateTime('2016-06-04')), '6' ],
            [ new Day(new \DateTime('2016-06-05')), '7' ],
            [ new Day(new \DateTime('2016-06-06')), '1' ],
        ];
    }

    public function provideDateTimeWithWeekDays()
    {
        return [
            [ new \DateTime('2016-05-30'), '1' ],
            [ new \DateTime('2016-05-31'), '2' ],
            [ new \DateTime('2016-06-01'), '3' ],
            [ new \DateTime('2016-06-02'), '4' ],
            [ new \DateTime('2016-06-03'), '5' ],
            [ new \DateTime('2016-06-04'), '6' ],
            [ new \DateTime('2016-06-05'), '7' ],
            [ new \DateTime('2016-06-06'), '1' ],
        ];
    }


    /**
     * @var Day
     */
    private static $day;

    public static function setUpBeforeClass()
    {
        self::$day = new Day(new \DateTime('2016-01-31 17:30:15'));
    }




    /*===========================================================
       TEST : class constants
    ===========================================================*/
    public function testConstantsAreCorrectlyDefined()
    {
        $this->assertEquals('1', Day::WEEK_MONDAY);
        $this->assertEquals('2', Day::WEEK_TUESDAY);
        $this->assertEquals('3', Day::WEEK_WEDNESDAY);
        $this->assertEquals('4', Day::WEEK_THURSDAY);
        $this->assertEquals('5', Day::WEEK_FRIDAY);
        $this->assertEquals('6', Day::WEEK_SATURDAY);
        $this->assertEquals('7', Day::WEEK_SUNDAY);
    }

    

    /*===========================================================
       TEST : __toString
    ===========================================================*/


    /**
     * @covers ::__toString
     *
     * @dataProvider provideValidYMDDates
     * @param Day $day
     * @param $expectedYMDDateStr
     */
    public function testToStringReturnCorrectFormattedDate(
        Day $day, $expectedYMDDateStr
    )
    {
        $this->assertEquals(
            $expectedYMDDateStr,
            (string)$day
        );
    }


    /*===========================================================
       TEST : getDate
    ===========================================================*/

    /**
     * @covers       ::getDate
     *
     * @dataProvider provideValidDates
     * @param Day $day
     * @param $expectedDateStr
     */
    public function testGetDateReturnCorrectDate(
        Day $day, $expectedDateStr
    )
    {
        $this->assertEquals(
            $expectedDateStr,
            $day->getDate()->format('Y-m-d H:i:s')
        );
    }




    /*===========================================================
       TEST : getYear
    ===========================================================*/

    /**
     * @covers ::getYear
     */
    public function testGetYearReturnAString()
    {
        $this->assertInternalType(
            'string',
            self::$day->getYear()
        );
    }

    /**
     * @covers ::getYear
     *
     * @dataProvider provideValidYears
     * @param Day       $day,
     * @param string    $expectedYear
     */
    public function testGetYearReturnCorrectYear(
        Day $day, $expectedYear
    )
    {
        $this->assertEquals(
            $expectedYear,
            $day->getYear()
        );
    }


    /*===========================================================
       TEST : getMonth
    ===========================================================*/

    /**
     * @covers ::getMonth
     */
    public function testGetMonthReturnAString()
    {
        $this->assertInternalType(
            'string',
            self::$day->getMonth()
        );
    }

    /**
     * @covers  ::getMonth
     *
     * @dataProvider provideValidMonths
     * @param Day $day
     * @param $expectedMonth
     */
    public function testGetMonthReturnCorrectMonth(
        Day $day, $expectedMonth
    )
    {
        $this->assertEquals(
            $expectedMonth,
            $day->getMonth()
        );
    }


    /*===========================================================
       TEST : getDay
    ===========================================================*/

    /**
     * @covers ::getDay
     */
    public function testGetDayReturnAString()
    {
        $this->assertInternalType(
            'string',
            self::$day->getDay()
        );
    }

    /**
     * @covers ::getDay
     *
     * @dataProvider provideValidDays
     * @param   Day     $day
     * @param   string  $expectedDay
     */
    public function testGetDayReturnCorrectDay(
        Day $day, $expectedDay
    )
    {
        $this->assertEquals(
            $expectedDay,
            $day->getDay()
        );
    }


    /*===========================================================
       TEST : getWeekDay
    ===========================================================*/

    /**
     * @covers ::getWeekDay
     */
    public function testGetWeekDayReturnAString()
    {
        $this->assertInternalType(
            'string',
            self::$day->getWeekDay()
        );
    }

    /**
     * @covers ::getWeekDay
     *
     * @dataProvider provideValidWeekDays
     * @param   Day $day
     * @param   string $expectedWeekDay
     */
    public function testGetWeekDayReturnCorrectDay(
        Day $day, $expectedWeekDay
    )
    {
        $this->assertEquals(
            $expectedWeekDay,
            $day->getWeekDay()
        );
    }


    /*===========================================================
       TEST : isOff && setOff
    ===========================================================*/
    /**
     * @covers ::isOff
     */
    public function testIsOffByDefaultIsFalse()
    {
        $this->assertFalse(self::$day->isOff());
    }

    /**
     * @covers ::setOff
     */
    public function testSetOffChangeIsOff()
    {
        $day = new Day(new \DateTime('2016-01-01'));
        $day->setOff(true);
        $this->assertTrue($day->isOff());
        $day->setOff(false);
        $this->assertFalse($day->isOff());
    }


    /*===========================================================
       TEST : isPast && setPast
    ===========================================================*/
    /**
     * @covers ::isPast
     */
    public function testIsPastByDefaultIsFalse()
    {
        $this->assertFalse(self::$day->isPast());
    }

    /**
     * @covers ::setPast
     */
    public function testSetPastChangeIsPast()
    {
        $day = new Day(new \DateTime('2016-01-01'));
        $day->setPast(true);
        $this->assertTrue($day->isPast());
        $day->setPast(false);
        $this->assertFalse($day->isPast());
    }


    /*===========================================================
       TEST : isReserved && setReserved
    ===========================================================*/
    /**
     * @covers ::isReserved
     */
    public function testIsReservedByDefaultIsFalse()
    {
        $this->assertFalse(self::$day->isReserved());
    }

    /**
     * @covers ::setReserved
     */
    public function testSetReservedChangeIsReserved()
    {
        $day = new Day(new \DateTime('2016-01-01'));
        $day->setReserved(true);
        $this->assertTrue($day->isReserved());
        $day->setReserved(false);
        $this->assertFalse($day->isReserved());
    }




    /*===========================================================
       TEST : equalsWeekDay
    ===========================================================*/
    /**
     * @covers ::equalsWeekDay
     */
    public function testEqualsWeekDayAreEquals()
    {
        $day = new Day(new \DateTime('2016-06-01'));
        $this->assertTrue($day->equalsWeekDay('3'));
    }

    /**
     * @covers ::equalsWeekDay
     */
    public function testEqualsWeekDayAreNotEquals()
    {
        $day = new Day(new \DateTime('2016-06-01'));
        $this->assertFalse($day->equalsWeekDay('4'));
    }




    /*===========================================================
       TEST : getWeekDayFrom
    ===========================================================*/
    /**
     * @covers ::getWeekDayFrom
     *
     * @dataProvider provideDateTimeWithWeekDays
     * @param   \DateTime $date
     * @param   string $expectedWeekDay
     */
    public function testGetWeekDayFromReturnWeekDay(
        \DateTime $date, $expectedWeekDay
        )
    {
        $this->assertEquals(
            $expectedWeekDay,
            Day::getWeekDayFrom($date)
        );
    }
}
