<?php
namespace Scheduler\Tests\Unit\Component\DateContainer;


use Scheduler\Component\DateContainer\Period;
use Scheduler\Component\DateContainer\PeriodIterator;

/**
 * Class PeriodTest
 * @covers \Scheduler\Component\DateContainer\Period::<public>
 * @coversDefaultClass \Scheduler\Component\DateContainer\Period
 */
class PeriodTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Period
     */
    private static $period;

    public static function setUpBeforeClass()
    {
        self::$period = new Period(
            new \DateTime('2015-09-01'),
            new \DateTime('2015-10-31'),
            'school year'
        );

    }



    /*==================================================================
        TEST : CONSTRUCTOR - Dates are correctly sorted
     ==================================================================*/

    /**
     * @covers ::__construct
     */
    public function testConstructorDatesIdentical()
    {
        $p = new Period(new \DateTime('2015-09-01'), new \DateTime('2015-09-01'));
        $this->assertTrue($p->getFirstDate() == $p->getLastDate());
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorDatesSorted()
    {
        $p = new Period(new \DateTime('2015-09-01'), new \DateTime('2015-10-31'));
        $this->assertTrue($p->getFirstDate() < $p->getLastDate());
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorDatesUnsortedThrowException()
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage(
            'The first date must be lower than the last date'
        );

        new Period(new \DateTime('2015-10-31'), new \DateTime('2015-09-01'));
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorWithAllArguments()
    {
        $desc    = "school year";

        $p = new Period(
            new \DateTime('2015-09-01'),
            new \DateTime('2016-07-31'),
            $desc
        );

        $this->assertEquals($desc, $p->getDescription());
    }

    /**
     * @covers ::__construct
     */
    public function testConstructorWithNoDescription()
    {
        $p = new Period(
            new \DateTime('2015-09-01'),
            new \DateTime('2016-07-31'),
            ''
        );

        $this->assertEquals('', $p->getDescription());
    }



    /*==================================================================
        TEST : getFirstDate
     ==================================================================*/

    /**
     * @covers ::getFirstDate
     */
    public function testGetFirstDateIsImmutable()
    {
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            self::$period->getFirstDate()
        );
    }

    /**
     * @covers ::getFirstDate
     */
    public function testGetFirstDateIsSameDateEntered()
    {
        $this->assertEquals(
            new \DateTimeImmutable('2015-09-01'),
            self::$period->getFirstDate()
        );
    }



    /*==================================================================
        TEST : getLastDate
     ==================================================================*/

    /**
     * @covers ::getLastDate
     */
    public function testGetLastDateIsImmutable()
    {
        $this->assertInstanceOf(
            \DateTimeImmutable::class,
            self::$period->getLastDate()
        );
    }

    /**
     * @covers ::getLastDate
     */
    public function testGetLastDateIsSameDateEntered()
    {
        $this->assertEquals(
            new \DateTimeImmutable('2015-10-31'),
            self::$period->getLastDate()
        );
    }



    /*==================================================================
        TEST : getDescription
     ==================================================================*/

    /**
     * @covers ::getDescription
     */
    public function testGetDescriptionIsString()
    {
        $this->assertInternalType(
            'string',
            self::$period->getDescription()
        );
    }

    /**
     * @covers ::getDescription
     */
    public function testGetDescriptionIsSameValue()
    {
        $this->assertEquals(
            'school year',
            self::$period->getDescription()
        );
    }


    /*==================================================================
        TEST : isIncluded
     ==================================================================*/

    public function provideIncludedDates()
    {
        return array(
            'First day included'        => ['2015-09-01'],
            'In between day included'   => ['2015-09-30'],
            'Last day included'         => ['2015-10-16']
        );
    }

    /**
     * @covers ::isIncluded
     *
     * @dataProvider provideIncludedDates
     * @param string $date
     */
    public function testIsDateIncludedIncludedDates($date)
    {
        $p = new Period(new \DateTime('2015-09-01'), new \DateTime('2015-10-16'));

        $this->assertTrue( $p->isIncluded( new \DateTime($date) ) );
    }
    
    /**
     * @return array
     */
    public function provideExcludedDates()
    {
        return array(
            'The day before'    => ['2015-08-31'],
            'The day after'     => ['2015-10-17'],
            'The month before'  => ['2015-08-16'],
            'The month after'   => ['2015-11-16'],
            'The year before'   => ['2014-09-01'],
            'The year after'    => ['2015-10-17']
        );
    }

    /**
     * @covers ::isIncluded
     *
     * @dataProvider provideExcludedDates
     * @param string $date
     */
    public function testIsDateIncludedExcludedDates($date)
    {
        $p = new Period(new \DateTime('2015-09-01'), new \DateTime('2015-10-16'));

        $this->assertFalse( $p->isIncluded( new \DateTime($date) ) );
    }






    /*==================================================================
        TEST : getDayIterator
     ==================================================================*/

    /**
     * @covers ::getDayIterator
     * @uses \Scheduler\Component\DateContainer\PeriodIterator
     */
    public function testGetDayIterator()
    {
        $this->assertInstanceOf(
            PeriodIterator::class,
            self::$period->getDayIterator(),
            "getDayIterator returns a Period iterator"
            );
        $this->assertCount( 61, self::$period->getDayIterator(),
            "Day iterator (Period Iterator) must returns 61 days"
            );
    }




    /*==================================================================
        TEST : getMonthIterator
     ==================================================================*/

    /**
     * @covers ::getMonthIterator
     * @uses \Scheduler\Component\DateContainer\PeriodIterator
     */
    public function testGetMonthIterator()
    {
        $this->assertInstanceOf(
            PeriodIterator::class,
            self::$period->getMonthIterator(),
            "getMonthIterator returns a Period iterator"
        );
        $this->assertCount(2, self::$period->getMonthIterator(),
            "Month iterator (Period Iterator) must returns 2 months"
        );
    }



    /*==================================================================
        TEST : getIterator
     ==================================================================*/

    /**
     * @covers ::getIterator
     * @uses \Scheduler\Component\DateContainer\PeriodIterator
     */
    public function testGetIterator()
    {
        $this->assertInstanceOf(
            PeriodIterator::class,
            self::$period->getIterator(new \DateInterval('P1W'))
        );
    }

}
