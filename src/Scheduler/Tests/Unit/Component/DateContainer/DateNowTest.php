<?php
namespace Scheduler\Tests\Unit\Component\DateContainer;
use Scheduler\Component\DateContainer\DateNow;


/**
 * Class DateNowTest
 * @coversDefaultClass \Scheduler\Component\DateContainer\DateNow
 */
class DateNowTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getDate
     */
    public function testShouldReturnDateSystemNow()
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
    public function testShouldReturnTheDateDefinedInInstanciation()
    {
        $expectedDate = '2016-01-01';
        $dateNow = new DateNow($expectedDate);

        $this->assertEquals($expectedDate, $dateNow->getDate()->format('Y-m-d'),
            'Date object must be the same as the string date passed as argument'
        );
    }

    /**
     * @covers ::__construct
     */
    public function testShouldThrowExceptionWithInvalidDate()
    {
        $this->expectException(\InvalidArgumentException::class);
        new DateNow('2016-31-31');
    }
}
