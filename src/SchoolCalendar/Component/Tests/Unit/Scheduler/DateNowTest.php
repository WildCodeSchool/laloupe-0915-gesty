<?php
namespace SchoolCalendar\Component\Tests\Unit\Scheduler;
use SchoolCalendar\Component\Scheduler\DateNow;


/**
 * Class DateNowTest
 * @coversDefaultClass \SchoolCalendar\Component\Scheduler\DateNow
 */
class DateNowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getDate
     */
    public function testGetDateIsSystemNow()
    {
        $nowExpected = new \DateTime();
        $dateNow = new DateNow();

        $this->assertEquals($nowExpected->format('Y-m-d'), $dateNow->getDate()->format('Y-m-d'),
            'Date object must be the same as the system current date with default argument in DateNow instance'
        );
        $dateNow = new DateNow('');

        $this->assertEquals($nowExpected->format('Y-m-d'), $dateNow->getDate()->format('Y-m-d'),
            'Date object must be the same as the system current date with the empty string in DateNow instance'
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getDate
     */
    public function testGetDateIsTheDateDefinedInInstanciation()
    {
        $expectedDate = '2016-01-01';
        $dateNow = new DateNow($expectedDate);


        $this->assertEquals($expectedDate, $dateNow->getDate()->format('Y-m-d'),
            'Date object must be the same as the string date passed as argument'
        );
    }

    /**
     * @covers ::__construct
     * @covers ::getDate
     * @covers ::getDateStr
     */
    public function testGetDateStrHelperReturnTheDate()
    {
        $dateNow = new DateNow('2016-01-01');

        $this->assertEquals($dateNow->getDate()->format('Y-m-d'), $dateNow->getDateStr(),
            'Date string helper method must return the same date'
        );
    }

    /**
     * @covers ::__construct
     */
    public function testThrowExceptionWithInvalidDate()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DateNow('2016-31-31');
    }
}
