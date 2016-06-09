<?php
namespace WCS\CalendrierBundle\Tests\Unit\Service;

use WCS\CalendrierBundle\Service\DateNow;
use WCS\CalendrierBundle\Service\DaysOffInterface;
use WCS\CalendrierBundle\Service\Service;
use WCS\CalendrierBundle\Service\Periode\Periode;


class ServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $file = '';
    protected $dateNow;
    protected $mockDayOff;

    protected function setUp()
    {
        $this->file = __DIR__ . '/../../Files/Calendrier_Scolaire_Zone_B.ics';
        $this->dateNow = new DateNow('2016-01-01');
        $this->mockDayOff = $this->createMock(DaysOffInterface::class);
    }


    /*==================================================================
     * Test la récupération du calendrier pour une année donnée
     ==================================================================*/
    public function anneesScolairesProvider()
    {
        return array(
            array('2015', '2015-09-01', '2016-07-05'),
            array('2016', '2016-09-01', '2017-07-08'),
            array('2017', '2017-09-04', '2018-07-07')
        );
    }

    /**
     * @dataProvider anneesScolairesProvider
     */
    public function testRecupererAnneeScolaire($anneeScolaire, $expectedDateDebut, $expectedDateFin)
    {
        $calService = new Service($this->file, $this->dateNow, $this->mockDayOff);
        $calService->selectRentreeScolaire($anneeScolaire);
        $cal = $calService->getPeriodesAnneeRentreeScolaire();

        $periodeExpected = new Periode($expectedDateDebut, $expectedDateFin);

        $this->assertEquals(
            $periodeExpected->getDebut(),
            $cal->getAnneeScolaire()->getDebut(),
            "Date de début d'année scolaire $anneeScolaire attendue : $expectedDateDebut"
        );

        $this->assertEquals(
            $periodeExpected->getFin(),
            $cal->getAnneeScolaire()->getFin(),
            "Date de fin d'année scolaire $anneeScolaire attendue : $expectedDateFin"
        );
    }

    /**
     * @dataProvider anneesScolairesProvider
     */
    public function testRecupererCalendrierScolaire($anneeScolaire, $expectedDateDebut, $expectedDateFin)
    {
        $calService = new Service($this->file, $this->dateNow, $this->mockDayOff);
        $calService->selectRentreeScolaire($anneeScolaire);
        $cal = $calService->getCalendrierRentreeScolaire();

        $periodeExpected = new Periode($expectedDateDebut, $expectedDateFin);

        $this->assertEquals(
            $periodeExpected->getDebut(),
            $cal->getPeriodesScolaire()->getAnneeScolaire()->getDebut(),
            "Date de début d'année scolaire $anneeScolaire attendue : $expectedDateDebut"
        );

        $this->assertEquals(
            $periodeExpected->getFin(),
            $cal->getPeriodesScolaire()->getAnneeScolaire()->getFin(),
            "Date de fin d'année scolaire $anneeScolaire attendue : $expectedDateFin"
        );
    }

    /**
     * 
     */
    public function testRecupererNombreAnneesScolaires()
    {
        $calService = new Service($this->file, $this->dateNow, $this->mockDayOff);
        $this->assertEquals(3, $calService->getNbAnneeScolaires());
    }


    /**
     * 
     */
    public function testRecupererNullSiCalendrierIntrouvable()
    {
        $calService = new Service($this->file, $this->dateNow, $this->mockDayOff);
        $calService->selectRentreeScolaire('2019');
        $cal = $calService->getCalendrierRentreeScolaire();

        $this->assertNull($cal);
    }


    /**
     *
     */
    public function testRecupererNullSiPeriodesScolaireIntrouvable()
    {
        $calService = new Service($this->file, $this->dateNow, $this->mockDayOff);
        $calService->selectRentreeScolaire('2019');
        $cal = $calService->getPeriodesAnneeRentreeScolaire();

        $this->assertNull($cal);
    }

    /*==================================================================
     * Test la récupération du calendrier pour une "date actuelle" donnée
     * Permet de s'assurer que la date fournie passe
     ==================================================================*/
    public function providerAnneesScolairesParDateDuJour()
    {
        return array(
            array('2015-09-01', '2015-09-01', '2016-07-05'),
            array('2015-10-20', '2015-09-01', '2016-07-05'),
            array('2015-12-31', '2015-09-01', '2016-07-05'),
            array('2016-01-01', '2015-09-01', '2016-07-05'),
            array('2016-04-20', '2015-09-01', '2016-07-05'),
            array('2016-07-05', '2015-09-01', '2016-07-05'),

            array('2016-09-01', '2016-09-01', '2017-07-08'),
            array('2016-10-20', '2016-09-01', '2017-07-08'),
            array('2016-12-31', '2016-09-01', '2017-07-08'),
            array('2017-01-01', '2016-09-01', '2017-07-08'),
            array('2017-04-20', '2016-09-01', '2017-07-08'),
            array('2017-07-08', '2016-09-01', '2017-07-08')
        );
    }

    /**
     * @dataProvider providerAnneesScolairesParDateDuJour
     */
    public function testRecupererAnneeScolaireParDateDuJour($dateDuJour, $expectedDateDebut, $expectedDateFin)
    {
        $calService = new Service($this->file, $this->dateNow, $this->mockDayOff);
        $calService->selectRentreeScolaireAvecDate($dateDuJour);
        $cal = $calService->getCalendrierRentreeScolaire();

        $periodeExpected = new Periode($expectedDateDebut, $expectedDateFin);

        $this->assertEquals(
            $periodeExpected->getDebut(),
            $cal->getPeriodesScolaire()->getAnneeScolaire()->getDebut(),
            "Date de début d'année scolaire $dateDuJour attendue : $expectedDateDebut"
        );

        $this->assertEquals(
            $periodeExpected->getFin(),
            $cal->getPeriodesScolaire()->getAnneeScolaire()->getFin(),
            "Date de fin d'année scolaire $dateDuJour attendue : $expectedDateFin"
        );

        $this->assertEquals(
            $dateDuJour,
            $cal->getDateToday()
        );

    }

    public function testRecupererAnneeScolaireSansDate()
    {
        $calService = new Service($this->file, $this->dateNow, $this->mockDayOff);
        $cal = $calService->getCalendrierRentreeScolaire();

        $this->assertEquals(
            $this->dateNow->getDateStr(),
            $cal->getDateToday()
        );

    }
}