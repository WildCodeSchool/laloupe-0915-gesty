<?php
namespace WCS\CalendrierBundle\Tests\Integration\Service;

use WCS\CalendrierBundle\Service\Service;
use WCS\CalendrierBundle\Service\PeriodesAnneeScolaire\PeriodesAnneeScolaire;

class CalendrierService extends \PhpUnit_Framework_TestCase
{
    protected $file = '';
    /**
     * @var \WCS\CalendrierBundle\Service\Service
     */
    protected $service;

    protected function setUp()
    {
        $this->file = __DIR__ . '/../../Files/Calendrier_Scolaire_Zone_B.ics';
        $this->service = new Service($this->file);
    }

    public function providerPeriodesScolaires()
    {
        return array(
            ['2015', '2015-09-01', '2015-10-17', true, PeriodesAnneeScolaire::CLASSE_RENTREE],
            ['2015', '2015-10-18', '2015-11-01', false, PeriodesAnneeScolaire::VACANCE_TOUSSAINT],
            ['2015', '2015-11-02', '2015-12-19', true, PeriodesAnneeScolaire::CLASSE_TOUSSAINT_NOEL],
            ['2015', '2015-12-20', '2016-01-03', false, PeriodesAnneeScolaire::VACANCE_NOEL],
            ['2015', '2016-01-04', '2016-02-06', true, PeriodesAnneeScolaire::CLASSE_NOEL_HIVER],
            ['2015', '2016-02-07', '2016-02-21', false, PeriodesAnneeScolaire::VACANCE_HIVER],
            ['2015', '2016-02-22', '2016-04-02', true, PeriodesAnneeScolaire::CLASSE_HIVER_PRINTEMPS],
            ['2015', '2016-04-03', '2016-04-17', false, PeriodesAnneeScolaire::VACANCE_PRINTEMPS],
            ['2015', '2016-04-18', '2016-07-05', true, PeriodesAnneeScolaire::CLASSE_PRINTEMPS_ETE],

            ['2016', '2016-09-01', '2016-10-19', true, PeriodesAnneeScolaire::CLASSE_RENTREE],
            ['2016', '2016-10-20', '2016-11-02', false, PeriodesAnneeScolaire::VACANCE_TOUSSAINT],
            ['2016', '2016-11-03', '2016-12-17', true, PeriodesAnneeScolaire::CLASSE_TOUSSAINT_NOEL],
            ['2016', '2016-12-18', '2017-01-02', false, PeriodesAnneeScolaire::VACANCE_NOEL],
            ['2016', '2017-01-03', '2017-02-11', true, PeriodesAnneeScolaire::CLASSE_NOEL_HIVER],
            ['2016', '2017-02-12', '2017-02-26', false, PeriodesAnneeScolaire::VACANCE_HIVER],
            ['2016', '2017-02-27', '2017-04-08', true, PeriodesAnneeScolaire::CLASSE_HIVER_PRINTEMPS],
            ['2016', '2017-04-09', '2017-04-23', false, PeriodesAnneeScolaire::VACANCE_PRINTEMPS],
            ['2016', '2017-04-24', '2017-07-08', true, PeriodesAnneeScolaire::CLASSE_PRINTEMPS_ETE],

            ['2017', '2017-09-04', '2017-10-21', true, PeriodesAnneeScolaire::CLASSE_RENTREE],
            ['2017', '2017-10-22', '2017-11-05', false, PeriodesAnneeScolaire::VACANCE_TOUSSAINT],
            ['2017', '2017-11-06', '2017-12-23', true, PeriodesAnneeScolaire::CLASSE_TOUSSAINT_NOEL],
            ['2017', '2017-12-24', '2018-01-07', false, PeriodesAnneeScolaire::VACANCE_NOEL],
            ['2017', '2018-01-08', '2018-02-24', true, PeriodesAnneeScolaire::CLASSE_NOEL_HIVER],
            ['2017', '2018-02-25', '2018-03-11', false, PeriodesAnneeScolaire::VACANCE_HIVER],
            ['2017', '2018-03-12', '2018-04-21', true, PeriodesAnneeScolaire::CLASSE_HIVER_PRINTEMPS],
            ['2017', '2018-04-22', '2018-05-06', false, PeriodesAnneeScolaire::VACANCE_PRINTEMPS],
            ['2017', '2018-05-07', '2018-07-07', true, PeriodesAnneeScolaire::CLASSE_PRINTEMPS_ETE],

        );
    }

    /**
     * @dataProvider providerPeriodesScolaires
     * @param $annee
     * @param $expectedDebut
     * @param $expectedFin
     * @param $estEnClasse
     * @param $indexPeriode
     */
    public function testPeriodesScolairesValide($annee, $expectedDebut, $expectedFin, $estEnClasse, $indexPeriode)
    {
        $this->service->selectRentreeScolaire($annee);
        $cal = $this->service->getPeriodesAnneeRentreeScolaire();

        if ($estEnClasse) {
            $periodes = $cal->getPeriodesEnClasse();
        }
        else {
            $periodes = $cal->getPeriodesEnVacance();
        }

        $this->assertEquals($annee, $cal->getAnneeScolaire()->getDebut()->format('Y'));
        $this->assertEquals($expectedDebut, $periodes->get($indexPeriode)->getDebut()->format('Y-m-d'));
        $this->assertEquals($expectedFin, $periodes->get($indexPeriode)->getFin()->format('Y-m-d'));
    }

}