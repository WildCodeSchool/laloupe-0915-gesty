<?php
namespace WCS\CalendrierBundle\Tests\Unit\Service\ICSFileReader;
use WCS\CalendrierBundle\Service\ICalendarFileReader\ICalendarFileReader;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\ICSFileReader\Exception\FileNotFoundException;
use WCS\CalendrierBundle\Service\ICSFileReader\Exception\InvalidFileException;
use WCS\CalendrierBundle\Service\ICSFileReader\Exception\NoFilenameException;


class ICSFileReaderTest extends \PHPUnit_Framework_TestCase
{
    private static $file_correct = __DIR__ . "/../../../Files/Calendrier_Scolaire_Zone_B.ics";
    private static $file_invalid = __DIR__ . "/../../../Files/fake.ics";

    /*==================================================================
     * S'assure que les exceptions sont correctement levées
     ==================================================================*/
    public function providerException()
    {
        return array(
            [ NoFilenameException::class,   ''],
            [ FileNotFoundException::class,  'inexistant_file.ics'],
            [ InvalidFileException::class, self::$file_invalid]
        );
    }

    /**
     * @dataProvider providerException
     */
    public function testExceptions($exception, $filepath)
    {
        $this->setExpectedException( $exception );
        new ICSFileReader($filepath);
    }

    /*==================================================================
     * S'assure que les périodes sont créées correctement
     ==================================================================*/
    /**
     * @var WCS\CalendrierBundle\CalendrierScolaire\ListPeriodes\ListPeriodes
     */
    private static $events;

    public static function setUpBeforeClass()
    {
        $cal = new ICSFileReader(self::$file_correct);
        self::$events = $cal->getEvents();
    }

    /**
     * S'assure que le bon nombre de période est retourné.
     * Ici, le fichier de tests comporte 21 évènements
     */
    public function testNbPeriodes()
    {
        $this->assertCount(21, self::$events);
    }


    public function providerPeriodes()
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
     * @dataProvider providerPeriodes
     * @param $expectedDateDebut
     * @param $expectedDateFin
     * @param $expectedDesc
     * @param $indexInCalendar
     */
    public function testPeriodes($expectedDateDebut, $expectedDateFin, $expectedDesc, $indexInCalendar)
    {
        $expected = new Periode($expectedDateDebut, $expectedDateFin, $expectedDesc);
        $event = self::$events->get($indexInCalendar);

        $this->assertEquals($expected->getDebut(),$event->getDebut(), "Date de debut");
        $this->assertEquals($expected->getFin(),$event->getFin(), "Date de fin");
        $this->assertEquals($expected->getDescription(),$event->getDescription(), "Description");
    }

}
