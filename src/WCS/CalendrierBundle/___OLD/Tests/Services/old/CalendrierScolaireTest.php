<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 16/05/16
 * Time: 11:23
 */

namespace WCS\CalendrierBundle\Tests\Services;

use WCS\CalendrierBundle\Services\CalendrierPeriode;
use WCS\CalendrierBundle\Services\CalendrierScolaire;


class CalendrierScolaireTest extends \PhpUnit_Framework_TestCase
{
    protected $file = '';

    protected function setUp()
    {
        $this->file = __DIR__ . '/files/Calendrier_Scolaire_Zone_B.ics';
    }


    /*==================================================================
     * Test si les exceptions sont bien relayées
     ==================================================================*/
    public function testNoException()
    {
        try {
            new CalendrierScolaire("2015", $this->file);
        }
        catch(\Exception $e) {
            $this->fail("Aucune exception ne doit être levée ici. Voici le message : ".$e->getMessage());
        }
    }

    public function testException()
    {
        $this->setExpectedException('WCS\CalendrierBundle\Services\CalendrierException');
        new CalendrierScolaire("2015", '');
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
        $cal = new CalendrierScolaire($anneeScolaire, $this->file);

        $periodeExpected = new CalendrierPeriode(
            new \DateTime($expectedDateDebut),
            new \DateTime($expectedDateFin)
        );

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

    public function testRecupererAnneeScolaireNonDefinie()
    {
        $this->setExpectedException('WCS\CalendrierBundle\Services\CalendrierException');
        new CalendrierScolaire("2032", $this->file);
    }



    /*==================================================================
     * Vérifie si on renvoit le bon nombre de périodes
     ==================================================================*/
    public function testRecupererNombreCorrectDePeriodes()
    {
        $cal = new CalendrierScolaire("2016", $this->file);
        $periodes = $cal->getPeriodes();
        $this->assertCount(CalendrierScolaire::NB_EVENTS_PAR_AN, $periodes);
    }

    public function testRecupererNombreCorrectDePeriodesEnVacance()
    {
        $cal = new CalendrierScolaire("2016", $this->file);
        $periodes = $cal->getPeriodesEnVacance();
        $this->assertCount(4, $periodes);
    }

    public function testRecupererNombreCorrectDePeriodeEnClasse()
    {
        $cal = new CalendrierScolaire("2016", $this->file);
        $periodes = $cal->getPeriodesEnClasse();
        $this->assertCount(5, $periodes);
    }


    /*==================================================================
     * Vérifie si les descriptions renvoyées sont les bonnes
     ==================================================================*/
    public function anneeProvider()
    {
        return array(
            array("2015"),
            array("2016"),
            array("2017"),
        );
    }

    /**
     * @dataProvider anneeProvider
     */
    public function testRecupererDescriptionsVacances($annee)
    {
        $cal = new CalendrierScolaire($annee, $this->file);
        $expected = array(
            "Vacances de la Toussaint",
            "Vacances de Noël",
            "Vacances d'hiver",
            "Vacances de printemps",
            "Vacances d'été"
        );
        $periodes = $cal->getPeriodesEnVacance();

        foreach ($periodes as $index => $periode) {
            $this->assertEquals($expected[$index], $periode->getDescription());
        }
    }

    /*==================================================================
     * Vérifie si les dates des périodes renvoyées sont les bonnes
     ==================================================================*/
    public function providerPeriodes()
    {
        return [
            'siVacanceToussaint' => [
                CalendrierScolaire::VACANCE_TOUSSAINT, "getPeriodesEnVacance",
                '2015-10-17', '2015-11-01'
            ],
            'siVacanceNoel' => [
                CalendrierScolaire::VACANCE_NOEL, "getPeriodesEnVacance",
                '2015-12-19', '2016-01-03'
            ],
            'siVacanceHiver' => [
                CalendrierScolaire::VACANCE_HIVER, "getPeriodesEnVacance",
                '2016-02-06', '2016-02-21'
            ],
            'siVacancePrintemps' => [
                CalendrierScolaire::VACANCE_PRINTEMPS, "getPeriodesEnVacance",
                '2016-04-02', '2016-04-17'
            ],
            
            'siEnClasseEntreRentreeToussaint' => [
                CalendrierScolaire::CLASSE_RENTREE, "getPeriodesEnClasse",
                '2015-09-01', '2015-10-17'
            ],
            'siEnClasseEntreToussaintNoel' => [
                CalendrierScolaire::CLASSE_TOUSSAINT_NOEL, "getPeriodesEnClasse",
                '2015-11-02', '2015-12-19'
            ],
            'siEnClasseEntreNoelHiver' => [
                CalendrierScolaire::CLASSE_NOEL_HIVER, "getPeriodesEnClasse",
                '2016-01-04', '2016-02-06'
            ],
            'siEnClasseEntreHiverPrintemps' => [
                CalendrierScolaire::CLASSE_HIVER_PRINTEMPS, "getPeriodesEnClasse",
                '2016-02-22', '2016-04-02'
            ],
            'siEnClasseEntrePrintempsEte' => [
               CalendrierScolaire::CLASSE_PRINTEMPS_ETE, "getPeriodesEnClasse",
                '2016-04-18', '2016-07-05'
            ]
        ];
    }

    /**
     * @dataProvider providerPeriodes
     */
    public function testRecupererDatesPeriodes(
                        $indexPeriode,
                        $method,
                        $expectedDateDebut,
                        $expectedDateFin
                        )
    {
        $cal = new CalendrierScolaire("2015", $this->file);
        $pVacances = $cal->{$method}();

        $periode = $pVacances[$indexPeriode];

        $expected = new CalendrierPeriode(
            new \DateTime($expectedDateDebut),
            new \DateTime($expectedDateFin)
        );

        $this->assertEquals(
            $expected->getDebut(),
            $periode->getDebut(),
            ' date de debut');

        $this->assertEquals(
            $expected->getFin(),
            $periode->getFin(),
            ' date de fin');
    }


    /*==================================================================
     * S'assure que l'on récupère bien une période donnée
     ==================================================================*/
    public function periodeUniqueProvider()
    {
        return [
            'EVENT_PRE_RENTREE_2016' =>
                [ '2016', '2016-08-31', '2016-08-31', CalendrierScolaire::EVENT_PRE_RENTREE ],
            'EVENT_RENTREE_2016' =>
                [ '2016', '2016-09-01', '2016-09-01', CalendrierScolaire::EVENT_RENTREE ],
            'EVENT_TOUSSAINT_2016' =>
                [ '2016', '2016-10-19', '2016-11-02', CalendrierScolaire::EVENT_TOUSSAINT ],
            'EVENT_NOEL_2016' =>
                [ '2016', '2016-12-17', '2017-01-02', CalendrierScolaire::EVENT_NOEL ],
            'EVENT_HIVER_2016' =>
                [ '2016', '2017-02-11', '2017-02-26', CalendrierScolaire::EVENT_HIVER ],
            'EVENT_PRINTEMPS_2016' =>
                [ '2016', '2017-04-08', '2017-04-23', CalendrierScolaire::EVENT_PRINTEMPS ],
            'EVENT_ETE_2016' =>
                [ '2016', '2017-07-08', '2017-07-08', CalendrierScolaire::EVENT_ETE ]
        ];
    }

    /**
     * @dataProvider periodeUniqueProvider
     */
    public function testRecuperePeriode($annee, $expectedDebut, $expectedFin, $event)
    {
        $cal = new CalendrierScolaire($annee, $this->file);

        $periode = $cal->getPeriode($event);
        $this->assertEquals(new \DateTime($expectedDebut), $periode->getDebut());
        $this->assertEquals(new \DateTime($expectedFin), $periode->getFin());
    }


    /*==================================================================
     * S'assure que l'on récupère bien une période de classe donnée
     ==================================================================*/
    public function periodeEnVacanceUniqueProvider()
    {
        return [
            'VACANCE_TOUSSAINT_2016' =>
                [ '2016', '2016-10-19', '2016-11-02', CalendrierScolaire::VACANCE_TOUSSAINT ],
            'VACANCE_NOEL_2016' =>
                [ '2016', '2016-12-17', '2017-01-02', CalendrierScolaire::VACANCE_NOEL ],
            'VACANCE_HIVER_2016' =>
                [ '2016', '2017-02-11', '2017-02-26', CalendrierScolaire::VACANCE_HIVER ],
            'VACANCE_PRINTEMPS_2016' =>
                [ '2016', '2017-04-08', '2017-04-23', CalendrierScolaire::VACANCE_PRINTEMPS ],
        ];
    }

    /**
     * @dataProvider periodeEnVacanceUniqueProvider
     */
    public function testRecuperePeriodeVacance($annee, $expectedDebut, $expectedFin, $vacance)
    {
        $cal = new CalendrierScolaire($annee, $this->file);

        $periode = $cal->getVacances($vacance);
        $this->assertEquals(new \DateTime($expectedDebut), $periode->getDebut());
        $this->assertEquals(new \DateTime($expectedFin), $periode->getFin());
    }


    /*==================================================================
     * S'assure que l'on récupère bien une période de classe donnée
     ==================================================================*/
    public function periodeEnClasseUniqueProvider()
    {
        return [
            'CLASSE_RENTREE_2016' =>
                [ '2016', '2016-09-01', '2016-10-19', CalendrierScolaire::CLASSE_RENTREE ],
            'CLASSE_TOUSSAINT_NOEL_2016' =>
                [ '2016', '2016-11-03', '2016-12-17', CalendrierScolaire::CLASSE_TOUSSAINT_NOEL ],
            'CLASSE_NOEL_HIVER_2016' =>
                [ '2016', '2017-01-03', '2017-02-11', CalendrierScolaire::CLASSE_NOEL_HIVER ],
            'CLASSE_HIVER_PRINTEMPS_2016' =>
                [ '2016', '2017-02-27', '2017-04-08', CalendrierScolaire::CLASSE_HIVER_PRINTEMPS ],
            'CLASSE_PRINTEMPS_ETE_2016' =>
                [ '2016', '2017-04-24', '2017-07-08', CalendrierScolaire::CLASSE_PRINTEMPS_ETE ],
        ];
    }

    /**
     * @dataProvider periodeEnClasseUniqueProvider
     */
    public function testRecuperePeriodeClasse($annee, $expectedDebut, $expectedFin, $classe)
    {
        $cal = new CalendrierScolaire($annee, $this->file);

        $periode = $cal->getEnClasse($classe);
        $this->assertEquals(new \DateTime($expectedDebut), $periode->getDebut());
        $this->assertEquals(new \DateTime($expectedFin), $periode->getFin());
    }


    /*==================================================================
     * S'assure que l'on récupère la bonne période "en classe"
     * pour une date donnée
     ==================================================================*/
    public function periodeEnClasseProvider()
    {
        return [
            'ENCLASSE_RENTREE_2016_Entre_les_deux' =>
                [ '2016', '2016-09-29', '2016-09-01', '2016-10-19', CalendrierScolaire::PERIODE_ENCLASSE ],
            'ENCLASSE__TOUSSAINT_NOEL_2016_Entre_les_deux' =>
                [ '2016', '2016-12-02', '2016-11-03', '2016-12-17', CalendrierScolaire::PERIODE_ENCLASSE ],
            'ENCLASSE_NOEL_HIVER_2016_Entre_les_deux' =>
                [ '2016', '2017-01-03', '2017-01-03', '2017-02-11', CalendrierScolaire::PERIODE_ENCLASSE ],
            'ENCLASSE_HIVER_PRINTEMPS_2016_Entre_les_deux' =>
                [ '2016', '2017-03-31', '2017-02-27', '2017-04-08', CalendrierScolaire::PERIODE_ENCLASSE ],
            'ENCLASSE_PRINTEMPS_ETE_2016_Entre_les_deux' =>
                [ '2016', '2017-05-06', '2017-04-24', '2017-07-08', CalendrierScolaire::PERIODE_ENCLASSE ],
            'VACANCE_TOUSSAINT_2016_Entre_les_deux' =>
                [ '2016', '2016-10-25', '2016-10-19', '2016-11-02', CalendrierScolaire::PERIODE_ENVACANCE ],
            'PERIODE_NOEL_HIVER_2016_Entre_les_deux' =>
                [ '2016', '2017-01-03', '2017-01-03', '2017-02-11', CalendrierScolaire::PERIODE_TOUTES ]
        ];
    }

    /**
     * @dataProvider periodeEnClasseProvider
     */
    public function testRecupererPeriodeEnClassePourDateDonnee(
                        $annee,
                        $date,
                        $expectedDebut,
                        $expectedFin,
                        $periodeType
                        )
    {
        $cal = new CalendrierScolaire($annee, $this->file);

        $periode = $cal->getPeriodeADate(new \DateTime($date), $periodeType);
        $this->assertEquals(new \DateTime($expectedDebut), $periode->getDebut());
        $this->assertEquals(new \DateTime($expectedFin), $periode->getFin());
    }

    public function periodeHorsDateProvider()
    {
        return [
            'ENCLASSE_RENTREE_2016_Avant' =>
                [ '2016', '2016-08-31', CalendrierScolaire::PERIODE_ENCLASSE ],
            'ENCLASSE_RENTREE_2016_Apres' =>
                [ '2016', '2016-10-21', CalendrierScolaire::PERIODE_ENCLASSE ],
            'VACANCE_TOUSSAINT_2016_Avant' =>
                [ '2016', '2016-10-17', CalendrierScolaire::PERIODE_ENVACANCE ],
            'VACANCE_TOUSSAINT_2016_Apres' =>
                [ '2016', '2016-11-03', CalendrierScolaire::PERIODE_ENVACANCE ],
            'PERIODE_2016_Avant' =>
                [ '2016', '2016-07-30', CalendrierScolaire::PERIODE_TOUTES ],
            'PERIODE_2016_Apres' =>
                [ '2016', '2017-08-25', CalendrierScolaire::PERIODE_TOUTES ]
        ];
    }

    /**
     * @dataProvider periodeHorsDateProvider
     */
    public function testRecupererAucunePeriodeHorsDate(
                        $annee,
                        $date,
                        $periodeType
                        )
    {
        $cal = new CalendrierScolaire($annee, $this->file);

        $periode = $cal->getPeriodeADate(new \DateTime($date), $periodeType);
        $this->assertNull($periode);
    }
}