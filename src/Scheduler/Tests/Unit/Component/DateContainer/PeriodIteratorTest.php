<?php
/**
 * This unit test use the BDD style approach to cover all behavior of the class.
 * Given,
 * When,
 * Then.
 *
 * The "Given" and first "When" condition are performed by the setting up method for each tests
 * Additional "When" condition are performed by each method before executing assertions
 * The "Then" is performed by each method with assertions
 */
namespace Scheduler\Tests\Unit\Component\DateContainer;

use Scheduler\Component\DateContainer\Period;
use Scheduler\Component\DateContainer\PeriodInterface;
use Scheduler\Component\DateContainer\PeriodIterator;

/**
 * Class PeriodIteratorTest
 * @coversDefaultClass \Scheduler\Component\DateContainer\PeriodIterator
 */
class PeriodIteratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PeriodIterator
     */
    private $i;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $periodStub;

    /**
     * @return PeriodInterface
     */
    private function mockPeriodOneMonth()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject
         */
        $periodStub = $this->createMock(PeriodInterface::class);
        $periodStub->method('getFirstDate')
            ->willReturn(new \DateTimeImmutable('2016-01-01')
            );
        $periodStub->method('getLastDate')
            ->willReturn(new \DateTimeImmutable('2016-01-31')
            );
        return $periodStub;
    }

    /**
     * @return PeriodInterface
     */
    private function mockPeriodOneYear()
    {
        /**
         * @var \PHPUnit_Framework_MockObject_MockObject
         */
        $periodStub = $this->createMock(PeriodInterface::class);
        $periodStub->method('getFirstDate')
            ->willReturn(new \DateTimeImmutable('2016-01-01')
            );
        $periodStub->method('getLastDate')
            ->willReturn(new \DateTimeImmutable('2016-12-31')
            );
        return $periodStub;
    }

    /**
     * Given an iterator with a period over a month of 31 days
     * When instanciate the iterator with a daily increment
     */
    protected function setUp()
    {
        /**
         * @var Period $periodOneMonth
         */
        $this->periodStub = $this->mockPeriodOneMonth();
        $this->i = new PeriodIterator($this->periodStub, new \DateInterval('P1D'));
    }


    /**
     * Then key is 0
     *  And current is first date of the period
     *  And count return 31
     *  And is valid
     *
     * @covers ::<public>
     */
    public function testInstanciateOnly()
    {
        $this->assertEquals(0, $this->i->key(),
            'When instanciate the iterator Key is zero'
        );
        $this->assertEquals($this->periodStub->getFirstDate(), $this->i->current(),
            'When instanciate the iterator, the current value is the first date of the period'
        );
        $this->assertEquals(31, $this->i->count(),
            'When instanciate the iterator, the number of items is the total days of the period'
        );
        $this->assertTrue($this->i->valid(),
            'When instanciate the iterator, it is valid'
        );
    }


    /**
     * And when iterate to the second day.
     * Then key is 1
     *  And current is the second day of the period
     *  And count return 31
     *  And is valid
     *
     * @uses \Scheduler\Component\DateContainer\PeriodIterator::__construct
     * @covers ::next
     * @covers ::key
     * @covers ::current
     * @covers ::valid
     */
    public function testIterateToSecondDay()
    {
        $this->i->next();

        $this->assertEquals(1, $this->i->key(),
            'When instanciate the iterator Key is one'
        );
        $this->assertEquals('2016-01-02', $this->i->current()->format('Y-m-d'),
            'When instanciate the iterator, the current value is the second day of the period'
        );
        $this->assertTrue($this->i->valid(),
            'When instanciate the iterator, it is valid'
        );
    }


    /**
     * Given a period over a month of 31 days
     * When instanciate the iterator with a daily increment
     *  And iterate to the last day without using foreach
     * Then
     *  the iterator is not valid
     *  And key is equal to the total of days of the period
     *  And current is the last day of the period
     *
     * @uses \Scheduler\Component\DateContainer\PeriodIterator::__construct
     * @covers ::<public>
     */
    public function testIterateToLastDay()
    {
        foreach ($this->i as $date) {
        }

        $this->assertFalse($this->i->valid(),
            'When instanciate the iterator, it is invalid'
        );
        $this->assertEquals(31, $this->i->key(),
            'When instanciate to the last date, Key is 31'
        );
        $this->assertEquals($this->periodStub->getLastDate(), $this->i->current(),
            'When instanciate to the last date, the current value is the last date of the period'
        );

    }


    /**
     * Given a period over a month of 31 days
     * When instanciate the iterator with a daily increment
     *  And iterate to the last day using foreach
     * Then the foreach returned key is incremented by one
     *  And the foreach returned value is the next date
     *  And count does not change and stays at 31
     *
     * @uses \Scheduler\Component\DateContainer\PeriodIterator::__construct
     * @covers ::<public>
     */
    public function testIterateWithForeach()
    {
        $cpt = 0;
        $dateExpected = $this->periodStub->getFirstDate();
        $oneDay = new \DateInterval('P1D');

        foreach ($this->i as $key => $date) {
            $this->assertEquals($cpt, $key,
                'When iterate, the foreach returned key is incremented by one'
            );
            $this->assertEquals($dateExpected->format('Y-m-d'), $date->format('Y-m-d'),
                'When iterate, the foreach returned value is the next date'
            );

            $cpt++;
            $dateExpected = $dateExpected->add($oneDay);
        }
    }


    /**
     * Given a period over a month of 31 days
     * When instanciate the iterator with a daily increment
     *  And iterate to the last day using foreach
     * Then the foreach returned key is incremented by one
     *  And the foreach returned value is the next date
     *  And count does not change and stays at 31
     *
     * @uses \Scheduler\Component\DateContainer\PeriodIterator::__construct
     * @covers ::<public>
     */
    public function testIterateAndEnsureInvariant()
    {
        $cpt = 0;

        foreach ($this->i as $date) {
            $this->assertCount(31, $this->i,
                'When instanciate the iterator, the number of items is the total days of the period'
            );

            $cpt++;
        }
        $this->assertEquals(31, $cpt,
            'After iterate, every items have been counted. None has been skipped'
        );
    }

    /**
     * Given a period over a month of 31 days
     * When instanciate the iterator with a daily increment
     *  And iterate to the second day
     *  And rewind
     * Then the iterator is valid
     *  And key is equal to zero
     *  And current is the first day of the period
     *  And the count number is not changed
     *
     * @uses \Scheduler\Component\DateContainer\PeriodIterator::__construct
     * @covers ::rewind
     * @covers ::valid
     * @covers ::key
     * @covers ::next
     * @covers ::current
     * @covers ::count
     */
    public function testIterateAndRewind()
    {
        $this->i->next();
        $this->i->rewind();

        $this->assertTrue($this->i->valid(),
            'When iterate and rewind, it is invalid'
        );
        $this->assertEquals(0, $this->i->key(),
            'When iterate and rewind, Key is zero'
        );
        $this->assertEquals($this->periodStub->getFirstDate(), $this->i->current(),
            'When iterate and rewind, the current value is the first date of the period'
        );
        $this->assertCount(31, $this->i,
            'After rewind, the number of items is the total days of the period'
        );
    }

    /**
     * Constructor test case
     *
     * Given a period other 12 months
     * When instanciate an iterator with this period
     *  And with an increment of one month
     * Then
     *  count return 12
     *
     * @covers ::__construct
     * @uses \Scheduler\Component\DateContainer\PeriodIterator::count
     */
    public function testInstanciateWithMonthIncrement()
    {
        $periodStub = $this->mockPeriodOneYear();
        $monthIterator = new PeriodIterator($periodStub, new \DateInterval('P1M'));

        $this->assertCount(12, $monthIterator,
            'When instanciate the iterator, the number of items is the total months of the period'
        );
    }

}
