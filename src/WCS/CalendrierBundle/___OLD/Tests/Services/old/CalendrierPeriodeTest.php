<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 16/05/16
 * Time: 11:23
 */

namespace WCS\CalendrierBundle\Tests\Services;


use WCS\CalendrierBundle\Services\CalendrierPeriode;

class CalendrierPeriodeTest extends \PhpUnit_Framework_TestCase
{
    /*==================================================================
     * Test les getters et setters
     ==================================================================*/
    public function testConstructeurTousAttributsSontCorrects()
    {
        $dateDebut      = new \DateTime('2015-09-01');
        $dateFin        = new \DateTime('2016-07-31');
        $description    = "année scolaire";

        $periode = new CalendrierPeriode($dateDebut, $dateFin, $description);

        $this->assertEquals($dateDebut, $periode->getDebut());
        $this->assertEquals($dateFin, $periode->getFin());
        $this->assertEquals($description, $periode->getDescription());
    }

    public function testConstructeurSansDescriptionAttributsSontCorrects()
    {
        $dateDebut      = new \DateTime('2015-09-01');
        $dateFin        = new \DateTime('2016-07-31');

        $periode = new CalendrierPeriode($dateDebut, $dateFin);

        $this->assertEquals($dateDebut, $periode->getDebut());
        $this->assertEquals($dateFin, $periode->getFin());
        $this->assertEquals("", $periode->getDescription());
    }

    /*==================================================================
     * Test si une date est incluse ou non dans une période
     ==================================================================*/
    public function testDateEstInclusDansPeriode()
    {
        $dateDebut  = new \DateTime('2015-09-01');
        $dateFin    = new \DateTime('2015-10-16');

        $periode = new CalendrierPeriode($dateDebut, $dateFin);

        $this->assertTrue( $periode->isDateIncluded( new \DateTime('2015-09-01') ), 'Le premier jour' );
        $this->assertTrue( $periode->isDateIncluded( new \DateTime('2015-09-30') ), 'Un jour entre' );
        $this->assertTrue( $periode->isDateIncluded( new \DateTime('2015-10-16') ), 'Le dernier jour' );
    }

    public function testDateEstHorsPeriode()
    {
        $dateDebut  = new \DateTime('2015-09-01');
        $dateFin    = new \DateTime('2015-10-16');

        $periode = new CalendrierPeriode($dateDebut, $dateFin);

        $this->assertFalse( $periode->isDateIncluded( new \DateTime('2015-08-31') ), 'Un jour avant');
        $this->assertFalse( $periode->isDateIncluded( new \DateTime('2015-10-17') ), 'Un jour après');

        $this->assertFalse( $periode->isDateIncluded( new \DateTime('2015-08-01') ), 'Un mois avant' );
        $this->assertFalse( $periode->isDateIncluded( new \DateTime('2015-11-16') ), 'Un an après' );

        $this->assertFalse( $periode->isDateIncluded( new \DateTime('2014-09-01') ), 'Un an avant' );
        $this->assertFalse( $periode->isDateIncluded( new \DateTime('2015-10-17') ), 'Un an après' );
    }

    /*==================================================================
     * S'assure que les attributs ne sont pas modifiables
     ==================================================================*/
    public function testDatesSontNonModifiables()
    {
        $dDebut     = new \DateTime('2015-01-01');
        $dFin       = new \DateTime('2015-12-31');
        $desc       = "Test";

        $periode = new CalendrierPeriode($dDebut, $dFin, $desc);

        $periode->getDebut()->setDate('2016', '02', '02');
        $periode->getDebut()->setDate('2017', '08', '17');

        $desc2 = $periode->getDescription();
        $desc2 = "Test 2";

        $this->assertEquals($dDebut, $periode->getDebut(), "La date de debut ne doit pas être modifiable");
        $this->assertEquals($dFin, $periode->getFin(), "La date de fin ne doit pas être modifiable");
        $this->assertEquals($desc, $periode->getDescription(), "La description ne doit pas être modifiable");
    }
}