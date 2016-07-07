<?php
namespace WCS\CantineBundle\Tests\Integration\Service;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use WCS\CantineBundle\Service\GestyScheduler\ActivityType;
use WCS\CantineBundle\TestHelper\GestyFixturesWebTestCase;

use Scheduler\Component\DateContainer\Period;
use WCS\CantineBundle\Service\GestyScheduler\GestyScheduler;

class GestySchedulerTest extends WebTestCase
{
    /**=======================================================================================
     *
     *  SETUP TESTS
     *
    =======================================================================================*/

    /**
     * @var GestyFixturesWebTestCase
     */
    private static $fixture;

    /**
     * @var GestyScheduler
     */
    private static $gestyScheduler;

    /**
     * Through the database fixture,
     * all school dates are loaded from an ics file in DataFixtures\Files
     */
    public static function setUpBeforeClass()
    {
        self::$fixture = new GestyFixturesWebTestCase();
        self::$fixture->loadGestyFixtures();

        static::bootKernel();

        self::$gestyScheduler = new GestyScheduler(static::$kernel->getContainer()->get('doctrine'));
    }


    /**=======================================================================================
     *
     *  TEST : school years
     *
    =======================================================================================*/

    /**
     * Given a gesty scheduler
     *
     * When getting a school year period for any school year periods
     * from the scheduler
     *
     * Then the first date of the school years and the last date are the expected dates
     *
     * @coversNothing
     */
    public function testShouldHaveExpectedSchoolYear()
    {
        $scheduler = self::$gestyScheduler->getScheduler();

        $schoolYears = [
            new Period( new \DateTime('2015-09-01'),  new \DateTime('2016-07-05') ),
            new Period( new \DateTime('2016-09-01'),  new \DateTime('2017-07-07') ),
            new Period( new \DateTime('2017-09-04'),  new \DateTime('2018-07-06') )
        ];


        $i = 0;
        foreach($scheduler->getYearPeriods() as $period) {
            $this->assertEquals(
                $schoolYears[$i]->getFirstDate(),
                $period->getFirstDate(),
                '"date rentree scolaire" must be "'.$schoolYears[$i]->getFirstDate()->format('Y-m-d').'"'
            );
            $this->assertEquals(
                $schoolYears[$i]->getLastDate(),
                $period->getLastDate(),
                '"date fin annee scolaire" must be "'.$schoolYears[$i]->getLastDate()->format('Y-m-d').'"'
            );
            $i++;
        }
    }


    /**=======================================================================================
     *
     *  TESTS : periods
     *
    =======================================================================================*/

    public function provideSchoolHolidays()
    {
        return [
            // start, end, is classroom, description

            ['2015-09-01', '2015-10-16', true,  "CLASSE_RENTREE"],
            ['2015-10-17', '2015-11-01', false, "VACANCE_TOUSSAINT"],
            ['2015-11-02', '2015-12-18', true,  "CLASSE_TOUSSAINT_NOEL"],
            ['2015-12-19', '2016-01-03', false, "VACANCE_NOEL"],
            ['2016-01-04', '2016-02-05', true,  "CLASSE_NOEL_HIVER"],
            ['2016-02-06', '2016-02-21', false, "VACANCE_HIVER"],
            ['2016-02-22', '2016-04-01', true,  "CLASSE_HIVER_PRINTEMPS"],
            ['2016-04-02', '2016-04-17', false, "VACANCE_PRINTEMPS"],
            ['2016-04-18', '2016-07-05', true,  "CLASSE_PRINTEMPS_ETE"],

            ['2016-07-06', '2016-08-31', false,  "VACANCE_ETE"],

            ['2016-09-01', '2016-10-19', true,  "CLASSE_RENTREE"],
            ['2016-10-20', '2016-11-02', false, "VACANCE_TOUSSAINT"],
            ['2016-11-03', '2016-12-16', true,  "CLASSE_TOUSSAINT_NOEL"],
            ['2016-12-17', '2017-01-02', false, "VACANCE_NOEL"],
            ['2017-01-03', '2017-02-10', true,  "CLASSE_NOEL_HIVER"],
            ['2017-02-11', '2017-02-26', false, "VACANCE_HIVER"],
            ['2017-02-27', '2017-04-07', true,  "CLASSE_HIVER_PRINTEMPS"],
            ['2017-04-08', '2017-04-23', false, "VACANCE_PRINTEMPS"],
            ['2017-04-24', '2017-07-07', true,  "CLASSE_PRINTEMPS_ETE"],

            ['2017-07-08', '2017-09-03', false,  "VACANCE_ETE"],

            ['2017-09-04', '2017-10-20', true,  "CLASSE_RENTREE"],
            ['2017-10-21', '2017-11-05', false, "VACANCE_TOUSSAINT"],
            ['2017-11-06', '2017-12-22', true,  "CLASSE_TOUSSAINT_NOEL"],
            ['2017-12-23', '2018-01-07', false, "VACANCE_NOEL"],
            ['2018-01-08', '2018-02-23', true,  "CLASSE_NOEL_HIVER"],
            ['2018-02-24', '2018-03-11', false, "VACANCE_HIVER"],
            ['2018-03-12', '2018-04-20', true,  "CLASSE_HIVER_PRINTEMPS"],
            ['2018-04-21', '2018-05-06', false, "VACANCE_PRINTEMPS"],
            ['2018-05-07', '2018-07-06', true,  "CLASSE_PRINTEMPS_ETE"]
        ];
    }

    /**
     * return the number of holidays present in the provider
     * @return int
     */
    public function getProviderNbHolidays()
    {
        return count(array_filter($this->provideSchoolHolidays(),
            function($el) {
                return !$el[2];
            }
        ));
    }

    /**
     * return the number of periods in class present in the provider
     * @return int
     */
    public function getProviderNbInClassroom()
    {
        return count(array_filter($this->provideSchoolHolidays(),
            function($el) {
                return $el[2];
            }
        ));
    }

    /**
     * @coversNothing
     */
    public function testShouldHaveTheSameNumberOfPeriods()
    {
        $scheduler = self::$gestyScheduler->getScheduler();

        $this->assertCount(
            $this->getProviderNbInClassroom(),
            $scheduler->getAvailablePeriods(),
            'Should get the same number of periods in class'
        );

        $this->assertCount(
            $this->getProviderNbHolidays(),
            $scheduler->getPeriodsDayOff(),
            'Should get the same number of periods of holidays'
        );
    }

    /**
     * @dataProvider provideSchoolHolidays
     *
     * @param string $dateStart
     * @param string $dateEnd
     * @param bool $isInClassroom
     * @param string $description
     */
    public function testShouldHaveAllPeriodsOff($dateStart, $dateEnd, $isInClassroom, $description)
    {
        $scheduler = self::$gestyScheduler->getScheduler();

        if ($isInClassroom) {
            $periods = $scheduler->getAvailablePeriods();
        }
        else {
            $periods = $scheduler->getPeriodsDayOff();
        }

        $result = array_filter($periods,
            function (Period $item) use ($dateStart, $dateEnd) {
                return (
                    $dateStart == $item->getFirstDate()->format('Y-m-d')
                    &&
                    $dateEnd == $item->getLastDate()->format('Y-m-d')
                );
            }
        );
        $this->assertTrue(
            count($result) == 1,
            "Should have the period '$description' ($dateStart - $dateEnd)"
        );
    }


    public function provideDates()
    {
        return [
            // date, activity type, is available

            //-----------------
            // 2015 - 2016
            //-----------------

            // autour de la rentrée scolaire 2015
            // tuesday
            ['2015-09-01', ActivityType::CANTEEN, true],
            ['2015-09-01', ActivityType::TAP, true],
            ['2015-09-01', ActivityType::GARDERIE_MORNING, true],
            ['2015-09-01', ActivityType::GARDERIE_EVENING, true],

            // wednesday
            ['2015-09-02', ActivityType::CANTEEN, false],
            ['2015-09-02', ActivityType::TAP, false],
            ['2015-09-02', ActivityType::GARDERIE_MORNING, true],
            ['2015-09-02', ActivityType::GARDERIE_EVENING, false],

            // friday
            ['2015-09-04', ActivityType::CANTEEN, true],
            ['2015-09-04', ActivityType::TAP, false],
            ['2015-09-04', ActivityType::GARDERIE_MORNING, true],
            ['2015-09-04', ActivityType::GARDERIE_EVENING, true],

            // saturday
            ['2015-09-05', ActivityType::CANTEEN, false],
            ['2015-09-05', ActivityType::TAP, false],
            ['2015-09-05', ActivityType::GARDERIE_MORNING, false],
            ['2015-09-05', ActivityType::GARDERIE_EVENING, false],

            // autour des vacances de la Toussaint 2015

            // friday
            ['2015-10-16', ActivityType::CANTEEN, true],
            ['2015-10-16', ActivityType::TAP, false],
            ['2015-10-16', ActivityType::GARDERIE_MORNING, true],
            ['2015-10-16', ActivityType::GARDERIE_EVENING, true],

            // saturday
            ['2015-10-17', ActivityType::CANTEEN, false],
            ['2015-10-17', ActivityType::TAP, false],
            ['2015-10-17', ActivityType::GARDERIE_MORNING, false],
            ['2015-10-17', ActivityType::GARDERIE_EVENING, false],

            // tuesday (in holidays)
            ['2015-10-20', ActivityType::CANTEEN, false],
            ['2015-10-20', ActivityType::TAP, false],
            ['2015-10-20', ActivityType::GARDERIE_MORNING, false],
            ['2015-10-20', ActivityType::GARDERIE_EVENING, false],


            // autour des vacances d'été" 2016

            // tuesday
            ['2016-07-05', ActivityType::CANTEEN, true],
            ['2016-07-05', ActivityType::TAP, true],
            ['2016-07-05', ActivityType::GARDERIE_MORNING, true],
            ['2016-07-05', ActivityType::GARDERIE_EVENING, true],

            // wednesday (in holiday)
            ['2016-07-06', ActivityType::CANTEEN, false],
            ['2016-07-06', ActivityType::TAP, false],
            ['2016-07-06', ActivityType::GARDERIE_MORNING, false],
            ['2016-07-06', ActivityType::GARDERIE_EVENING, false],

            // friday (in holiday)
            ['2016-07-08', ActivityType::CANTEEN, false],
            ['2016-07-08', ActivityType::TAP, false],
            ['2016-07-08', ActivityType::GARDERIE_MORNING, false],
            ['2016-07-08', ActivityType::GARDERIE_EVENING, false],


            // 14 juillet 2016
            ['2016-07-14', ActivityType::CANTEEN, false],
            ['2016-07-14', ActivityType::TAP, false],
            ['2016-07-14', ActivityType::GARDERIE_MORNING, false],
            ['2016-07-14', ActivityType::GARDERIE_EVENING, false],


            //-----------------
            // 2016 - 2017
            //-----------------

            // autour de la rentrée scolaire 2016
            // thursday
            ['2016-09-01', ActivityType::CANTEEN, true],
            ['2016-09-01', ActivityType::TAP, true],
            ['2016-09-01', ActivityType::GARDERIE_MORNING, true],
            ['2016-09-01', ActivityType::GARDERIE_EVENING, true],

            // friday
            ['2016-09-02', ActivityType::CANTEEN, true],
            ['2016-09-02', ActivityType::TAP, false],
            ['2016-09-02', ActivityType::GARDERIE_MORNING, true],
            ['2016-09-02', ActivityType::GARDERIE_EVENING, true],

            // saturday (in holiday)
            ['2016-09-03', ActivityType::CANTEEN, false],
            ['2016-09-03', ActivityType::TAP, false],
            ['2016-09-03', ActivityType::GARDERIE_MORNING, false],
            ['2016-09-03', ActivityType::GARDERIE_EVENING, false],

            // autour des vacances de la Toussaint 2016
            // wednesday
            ['2016-10-19', ActivityType::CANTEEN, false],
            ['2016-10-19', ActivityType::TAP, false],
            ['2016-10-19', ActivityType::GARDERIE_MORNING, true],
            ['2016-10-19', ActivityType::GARDERIE_EVENING, false],

            // thursday (in holiday)
            ['2016-10-20', ActivityType::CANTEEN, false],
            ['2016-10-20', ActivityType::TAP, false],
            ['2016-10-20', ActivityType::GARDERIE_MORNING, false],
            ['2016-10-20', ActivityType::GARDERIE_EVENING, false],


            // autour des vacances d'état 2017
            // friday
            ['2017-07-07', ActivityType::CANTEEN, true],
            ['2017-07-07', ActivityType::TAP, false],
            ['2017-07-07', ActivityType::GARDERIE_MORNING, true],
            ['2017-07-07', ActivityType::GARDERIE_EVENING, true],

            // saturday (in holiday)
            ['2017-07-08', ActivityType::CANTEEN, false],
            ['2017-07-08', ActivityType::TAP, false],
            ['2017-07-08', ActivityType::GARDERIE_MORNING, false],
            ['2017-07-08', ActivityType::GARDERIE_EVENING, false],


            //-----------------
            // Jours fériés
            //-----------------

            // Paques 2016
            ['2016-03-28', ActivityType::CANTEEN, false],
            ['2016-03-28', ActivityType::TAP, false],
            ['2016-03-28', ActivityType::GARDERIE_MORNING, false],
            ['2016-03-28', ActivityType::GARDERIE_EVENING, false],

            // Ascension 2016
            ['2016-05-05', ActivityType::CANTEEN, false],
            ['2016-05-05', ActivityType::TAP, false],
            ['2016-05-05', ActivityType::GARDERIE_MORNING, false],
            ['2016-05-05', ActivityType::GARDERIE_EVENING, false],

            // Vendredi 2016
            ['2016-05-06', ActivityType::CANTEEN, false],
            ['2016-05-06', ActivityType::TAP, false],
            ['2016-05-06', ActivityType::GARDERIE_MORNING, false],
            ['2016-05-06', ActivityType::GARDERIE_EVENING, false],

        ];
    }

    /**
     * @dataProvider provideDates
     *
     * @param $date
     * @param $activityType
     * @param $isDayOffExpected
     */
    public function testShouldDatesAvailabilityBeCorrect($date, $activityType, $isDayAvailable)
    {
        $dateTime = new \DateTime($date);
        $strs = [
            ActivityType::CANTEEN => 'canteen',
            ActivityType::TAP => 'TAP',
            ActivityType::GARDERIE_MORNING => 'garderie morning',
            ActivityType::GARDERIE_EVENING => 'garderie evening'
        ];

        $strAssert = $dateTime->format('D')
            ." "
            .$date
            ." for "
            .$strs[$activityType]
            .($isDayAvailable?" should be day available":" should be day off");

        $this->assertEquals(
            $isDayAvailable,
            !self::$gestyScheduler->isDayOff($dateTime, $activityType),
            $strAssert
        );
    }
    
}
