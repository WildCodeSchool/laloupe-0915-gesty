<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 16/05/16
 * Time: 11:23
 */

namespace WCS\CalendrierBundle\Tests\Unit\CalendrierScolaire\Periode;


use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\Periode\Exception\InvalidArgumentException;
use WCS\CalendrierBundle\Service\Periode\Exception\InvalidDateException;
use WCS\CalendrierBundle\Service\Periode\Exception\IllSortedDatesException;
use WCS\CalendrierBundle\Service\Periode\Exception\PeriodeException;

class PeriodeTest extends \PhpUnit_Framework_TestCase
{
    /*==================================================================
     * Fournit un jeu de tests pour des dates ayant un type attendu.
     ==================================================================*/
    public function providerDateTypeAttendu()
    {
        return array(
            ['2015-09-01',                          '2016-07-31'],
            [new \DateTimeImmutable('2015-09-01'),  '2016-07-31'],
            [new \DateTime('2015-09-01'),           '2016-07-31'],
            ['2015-09-01',                          new \DateTimeImmutable('2016-07-31')],
            ['2015-09-01',                          new \DateTime('2016-07-31')]
        );
    }

    /*==================================================================
     * Fournit un jeu de tests pour des dates ayant un type non attendu.
     ==================================================================*/
    public function providerDateTypeRejete()
    {
        return array(
            ['',            '2016-07-31',   PeriodeException::DATE_DEBUT ],
            ['azerty',      '2016-07-31',   PeriodeException::DATE_DEBUT ],
            [20150901,      '2016-07-31',   PeriodeException::DATE_DEBUT ],
            ['20150901',    '2016-07-31',   PeriodeException::DATE_DEBUT ],
            ['150901',      '2016-07-31',   PeriodeException::DATE_DEBUT ],
            ['01/09/2015',  '2016-07-31',   PeriodeException::DATE_DEBUT ],

            ['2015-09-01',  '',             PeriodeException::DATE_FIN ],
            ['2015-09-01',  'azerty',       PeriodeException::DATE_FIN ],
            ['2015-09-01',  20160731,       PeriodeException::DATE_FIN ],
            ['2015-09-01',  '20160731',     PeriodeException::DATE_FIN ],
            ['2015-09-01',  '160731',       PeriodeException::DATE_FIN ],
            ['2015-09-01',  '31/07/2016',   PeriodeException::DATE_FIN ]
        );
    }

    /*==================================================================
     * Test le constructeur : les types d'arguments possibles
     ==================================================================*/
    /**
     *
     * @dataProvider providerDateTypeAttendu
     * @param $dateDebut
     * @param $dateFin
     */
    public function testDateTypeAttendu($dateDebut, $dateFin)
    {
        $exception = null;
        try {
            $p = new Periode($dateDebut, $dateFin);
        }
        catch(\Exception $e) {
            $exception = $e;
            $this->fail("Ce test ne doit pas lever d'exception. Message obtenu : ".$e->getMessage());
        }
        $this->assertNull($exception);
    }

    /**
     * @dataProvider providerDateTypeRejete
     * @param $dateDebut
     * @param $dateFin
     * @param $expectedExceptionCode
     */
    public function testDateTypeRejetes($dateDebut, $dateFin, $expectedExceptionCode)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode($expectedExceptionCode);

        $expectedMsg = 'possède un type non valide, seuls les \DateTime, \DateTimeImmutable et string au format \'Y-m-d\' sont autorisés';

        if ($expectedExceptionCode == PeriodeException::DATE_DEBUT) {
            $expectedMsg = 'La date de début '.$expectedMsg;
        }
        if ($expectedExceptionCode == PeriodeException::DATE_FIN) {
            $expectedMsg = 'La date de fin '.$expectedMsg;
        }
        $this->expectExceptionMessage($expectedMsg);


        new Periode($dateDebut, $dateFin);
    }

    /*==================================================================
     * Test le constructeur : les dates doivent être valides
     ==================================================================*/
    public function testDateDebutIncorrecte()
    {
        $dateDebut  = '2015-02-31';
        $dateFin    = '2016-07-31';

        $this->expectException(InvalidDateException::class);
        $this->expectExceptionCode(InvalidDateException::DATE_DEBUT);
        $this->expectExceptionMessage("Date de début non valide : ".$dateDebut);

        new Periode($dateDebut, $dateFin);
    }

    public function testDateFinIncorrecte()
    {
        $dateDebut  = '2015-09-01';
        $dateFin    = '2016-02-31';

        $this->expectException(InvalidDateException::class);
        $this->expectExceptionCode(InvalidDateException::DATE_FIN);
        $this->expectExceptionMessage("Date de fin non valide : ".$dateFin);

        new Periode($dateDebut, $dateFin);
    }

    public function testDatesDebutEtFinIdentiques()
    {
        $p = new Periode('2015-09-01', '2015-09-01');
        $this->assertTrue($p->getDebut() == $p->getFin());
    }

    public function testDatesDebutEtFinOrdonnees()
    {
        $p = new Periode('2015-09-01', '2016-07-31');
        $this->assertTrue($p->getDebut() < $p->getFin());
    }

    public function testDatesDebutEtFinDesordonnees()
    {
        $dateDebut  = new \DateTimeImmutable('2016-09-01');
        $dateFin    = new \DateTimeImmutable('2015-07-31');

        $this->expectException(IllSortedDatesException::class);
        $this->expectExceptionMessage(
            'Date de début doit être inférieure à la date de fin : ('.
            'ici date debut = '.$dateDebut->format('Y-m-d').', date fin = '.$dateFin->format('Y-m-d')
        );

        new Periode($dateDebut, $dateFin);

    }

    /*==================================================================
     * Test les getters et setters
     ==================================================================*/
    public function testConstructeurTousAttributsSontCorrects()
    {
        $dateDebut      = new \DateTime('2015-09-01');
        $dateFin        = new \DateTime('2016-07-31');
        $description    = "année scolaire";

        $periode = new Periode($dateDebut, $dateFin, $description);

        $this->assertEquals($dateDebut,     $periode->getDebut());
        $this->assertEquals($dateFin,       $periode->getFin());
        $this->assertEquals($description,   $periode->getDescription());
    }

    public function testConstructeurSansDescriptionAttributsSontCorrects()
    {
        $dateDebut      = new \DateTime('2018-09-01');
        $dateFin        = new \DateTime('2019-07-31');

        $periode = new Periode($dateDebut, $dateFin);

        $this->assertEquals($dateDebut, $periode->getDebut());
        $this->assertEquals($dateFin,   $periode->getFin());
        $this->assertEquals("",         $periode->getDescription());
    }

    /*==================================================================
     * Test si une date est incluse ou non dans une période
     ==================================================================*/
    public function providerDateTypeEstInclusAttendu()
    {
        return array(
            ['2015-09-01'                           ],
            [new \DateTime('2015-09-01')            ],
            [new \DateTimeImmutable('2015-09-01')   ]
        );
    }

    public function providerDateTypeEstInclusRejete()
    {
        return array(
            ['',            PeriodeException::DATE ],
            ['azerty',      PeriodeException::DATE ],
            [20150901,      PeriodeException::DATE ],
            ['20150901',    PeriodeException::DATE ],
            ['150901',      PeriodeException::DATE ],
            ['01/09/2015',  PeriodeException::DATE ]
        );
    }

    public function providerDateInclusDansPeriode()
    {
        return array(
            ['2015-09-01',  'Date chaine - Le premier jour'],
            ['2015-09-30',  'Date chaine - Un jour entre'],
            ['2015-10-16',  'Date chaine - Le dernier jour'],
        );
    }

    public function providerDateHorsPeriode()
    {
        return array(
            ['2015-08-31',  'Date chaine - Un jour avant'],
            ['2015-10-17',  'Date chaine - Un jour après'],
            ['2015-08-16',  'Date chaine - Un mois avant'],
            ['2015-11-16',  'Date chaine - Un mois après'],
            ['2014-09-01',  'Date chaine - Un an avant'],
            ['2015-10-17',  'Date chaine - Un an avant'],
        );
    }

    /**
     * @dataProvider providerDateTypeEstInclusAttendu
     * @param $dateTested
     * @param $messageAssertion
     */
    public function testDateTypeEstInclusAttendu($dateTested)
    {
            $periode = new Periode('2015-09-01', '2015-10-16');
            $result = $periode->isDateIncluded( $dateTested );
            $this->assertTrue($result);
    }

    /**
     * @dataProvider providerDateTypeEstInclusRejete
     * @param $dateTested
     * @param $messageAssertion
     */
    public function testDateTypeEstInclusRejete($dateTested)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionCode(PeriodeException::DATE);
        $expectedMsg = 'possède un type non valide, seuls les \DateTime, \DateTimeImmutable et string au format \'Y-m-d\' sont autorisés';

        $this->expectExceptionMessage("La date ".$expectedMsg);

        $periode = new Periode('2015-09-01', '2015-10-16');
        $periode->isDateIncluded( $dateTested );
    }

    public function testDateEstInclusInvalide()
    {
        $dateTested = '2015-02-31';

        $this->expectException(InvalidDateException::class);
        $this->expectExceptionCode(PeriodeException::DATE);
        $this->expectExceptionMessage("Date non valide : ".$dateTested);

        $periode = new Periode('2015-09-01', '2015-10-16');
        $periode->isDateIncluded( $dateTested );
    }

    /**
     * @dataProvider providerDateInclusDansPeriode
     * @param $dateTested
     * @param $messageAssertion
     */
    public function testDateEstInclusDansPeriode($dateTested, $messageAssertion)
    {
        $periode = new Periode('2015-09-01', '2015-10-16');

        $this->assertTrue( $periode->isDateIncluded( $dateTested ), $messageAssertion );
    }

    /**
     * @dataProvider providerDateHorsPeriode
     * @param $dateTested
     * @param $messageAssertion
     */
    public function testDateEstHorsPeriode($dateTested, $messageAssertion)
    {
        $periode = new Periode('2015-09-01', '2015-10-16');

        $this->assertFalse( $periode->isDateIncluded( $dateTested ), $messageAssertion );
    }

    /*==================================================================
     * S'assure que les attributs ne sont pas modifiables
     ==================================================================*/
    public function testDatesSontNonModifiables()
    {
        $dDebut     = new \DateTime('2015-01-01');
        $dFin       = new \DateTime('2015-12-31');
        $desc       = "Test";

        $periode = new Periode($dDebut, $dFin, $desc);

        $periode->getDebut()->setDate('2016', '02', '02');
        $periode->getDebut()->setDate('2017', '08', '17');

        $desc2 = $periode->getDescription();
        $desc2 = "Test 2";

        $this->assertEquals($dDebut, $periode->getDebut(), "La date de debut ne doit pas être modifiable");
        $this->assertEquals($dFin, $periode->getFin(), "La date de fin ne doit pas être modifiable");
        $this->assertEquals($desc, $periode->getDescription(), "La description ne doit pas être modifiable");
    }
}