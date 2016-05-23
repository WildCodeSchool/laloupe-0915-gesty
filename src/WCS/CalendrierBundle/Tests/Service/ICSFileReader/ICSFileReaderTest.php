<?php
namespace WCS\CalendrierBundle\Tests\Service\ICSFileReader;
use WCS\CalendrierBundle\Service\ICSFileReader\ICSFileReader;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\ICSFileReader\Exception\FileNotFoundException;
use WCS\CalendrierBundle\Service\ICSFileReader\Exception\InvalidFileException;
use WCS\CalendrierBundle\Service\ICSFileReader\Exception\NoFilenameException;
use WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes;


class ICSFileReaderTest extends \PhpUnit_Framework_TestCase
{
    /*==================================================================
     * S'assure que les exceptions sont correctement levées
     ==================================================================*/
    public function testNoException()
    {
        try {
            new ICSFileReader(__DIR__ . "/../files/Calendrier_Scolaire_Zone_B.ics");
        }
        catch(\Exception $e) {
            $this->fail("Aucune exception ne doit être levée ici. Message : ".$e->getMessage());
        }
    }

    public function providerException()
    {
        return array(
            [ NoFilenameException::class,   ''],
            [ FileNotFoundException::class,  'nofile.ics'],
            [ InvalidFileException::class, __DIR__ . "/../files/fake.ics"]
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
        $cal = new ICSFileReader(__DIR__ . "/../files/Calendrier_Scolaire_Zone_B.ics");
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
     * @param $dateDebut
     * @param $dateFin
     * @param $desc
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