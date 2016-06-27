<?php
namespace Scheduler\Tests\Unit\Component\FileReader;

use Scheduler\Component\DateContainer\Period;
use Scheduler\Component\FileReader\ICalendarFileReader;
use Scheduler\Component\FileReader\Exception\FileNotFoundException;
use Scheduler\Component\FileReader\Exception\InvalidFileException;
use Scheduler\Component\FileReader\Exception\NoFilenameException;

/**
 * Class ICalendarFileReaderTest
 * @coversDefaultClass \Scheduler\Component\FileReader\ICalendarFileReader
 */
class ICalendarFileReaderTest extends \PHPUnit_Framework_TestCase
{
    private static $file_correct = __DIR__ . "/../../../Files/Calendrier_Scolaire_Zone_B.ics";
    private static $file_invalid = __DIR__ . "/../../../Files/fake.ics";

    /*==================================================================
     * Ensure exceptions are throwns
     ==================================================================*/
    public function provideException()
    {
        return array(
            [ NoFilenameException::class,   ''],
            [ FileNotFoundException::class,  'inexistant_file.ics'],
            [ InvalidFileException::class, self::$file_invalid]
        );
    }

    /**
     * @dataProvider provideException
     *
     * @covers ::__construct()
     * @covers ::loadEvents()
     * @covers Scheduler\Component\FileReader\Exception\NoFilenameException
     * @covers Scheduler\Component\FileReader\Exception\FileNotFoundException
     * @covers Scheduler\Component\FileReader\Exception\InvalidFileException
     */
    public function testShouldThrow($exception, $filepath)
    {
        $this->expectException($exception);

        $cal = new ICalendarFileReader($filepath);
        $cal->loadEvents();
    }

    /*==================================================================
     * Ensure all events are read correctly
     ==================================================================*/
    /**
     * @var Period[]
     */
    private static $events;

    /**
     */
    public static function setUpBeforeClass()
    {
        $cal = new ICalendarFileReader(self::$file_correct);
        self::$events = $cal->loadEvents();
    }

    /**
     * Ensure that the expected number of periods are returned
     * In this test case, the file contains 21 events
     * @covers ::loadEvents()
     */
    public function testShouldReturnCorrectPeriodsNumber()
    {
        $this->assertCount(21, self::$events);
    }


    public function providerPeriods()
    {
        return array(
            // première date (date identique)
            [ '2015-08-31', '2015-08-31', "Prérentrée des enseignants", 0],

            // dernière date (date identique)
            [ '2018-07-07', '2018-07-07', "Vacances d'été", 20],

            // dates différentes
            [ '2016-02-06', '2016-02-21', "Vacances d'hiver", 4],
            [ '2016-10-19', '2016-11-02', 'Vacances de la Toussaint', 9]
        );
    }

    /**
     * Ensure arbitrary periods are correctly read.
     *
     * @dataProvider providerPeriods
     * @param string $startingDate
     * @param string $endingDate
     * @param string $desc
     * @param integer $indexInCalendar
     *
     * @uses \Scheduler\Component\FileReader\ICalendarFileReader
     * @uses \Scheduler\Component\DateContainer\Period
     */
    public function testShouldHaveCorrectPeriod(
        $startingDate,
        $endingDate,
        $desc,
        $indexInCalendar
    )
    {
        $expected = new Period(
            new \DateTime($startingDate),
            new \DateTime($endingDate),
            $desc
        );
        $event = self::$events[$indexInCalendar];

        $this->assertEquals($expected->getFirstDate(),$event->getFirstDate(), "Starting date");
        $this->assertEquals($expected->getLastDate(),$event->getLastDate(), "End date");
        $this->assertEquals($expected->getDescription(),$event->getDescription(), "Description");
    }

}
