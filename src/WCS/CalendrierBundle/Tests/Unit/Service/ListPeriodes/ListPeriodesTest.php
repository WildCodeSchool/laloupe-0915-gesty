<?php
namespace WCS\CalendrierBundle\Tests\Unit\Service\ListPeriodes;

use WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes;
use WCS\CalendrierBundle\Service\Periode\Periode;

class ListPeriodesTest extends \PHPUnit_Framework_TestCase
{
    /*==================================================================
     * Test : Constructor
     ==================================================================*/
    public function providerConstructorNbElements()
    {
        return [
            "Aucune_Periode" =>
                [ 0, array() ],
            "Une_Periode"   =>
                [ 1, array(new Periode('2015-01-01', '2015-01-02')) ],
            "Deux_Periodes" =>
                [ 2, array(new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')) ]
        ];
    }

    /**
     * @dataProvider providerConstructorNbElements
     * @param $nbExpected
     * @param $array_periodes
     */
    public function testConstructorNbElements($nbExpected, $array_periodes)
    {
        $obj = new ListPeriodes($array_periodes);

        $this->assertEquals($nbExpected, iterator_count($obj));
    }

    /*==================================================================
     * Test : get
     ==================================================================*/
    public function testListPeriodesGetNullSiTableauVide()
    {
        $array = [];
        $obj = new ListPeriodes($array);

        $this->assertNull($obj->get(0));
    }

    public function testListPeriodesGetNullSiIndexErrone()
    {
        $array = [new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')];
        $obj = new ListPeriodes($array);

        $this->assertNull($obj->get(15));
        $this->assertNull($obj->get(-5));
        $this->assertNull($obj->get('test'));
    }

    public function testListPeriodesGetPeriode()
    {
        $expected = [new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')];
        $obj = new ListPeriodes($expected);

        $this->assertEquals($expected[0], $obj->get(0));
        $this->assertEquals($expected[1], $obj->get(1));
    }


    /*==================================================================
     * Test : current
     ==================================================================*/
    public function testListPeriodesCurrentNullSiVide()
    {
        $expected = [];
        $obj = new ListPeriodes($expected);

        $this->assertNull($obj->current());
    }

    public function testListPeriodesCurrentPremierElement()
    {
        $expected = [new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')];
        $obj = new ListPeriodes($expected);

        $this->assertEquals($expected[0], $obj->current());
    }



    /*==================================================================
     * Test : key
     ==================================================================*/
    public function testKeyEstNumerique()
    {
        $expected = [new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')];
        $obj = new ListPeriodes($expected);

        $this->assertInternalType('numeric', $obj->key());
    }

    public function tesKeyIndexElementEnCours()
    {
        $expected = [new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')];
        $obj = new ListPeriodes($expected);

        $this->assertEquals(0, $obj->key());
        $obj->next();
        $this->assertEquals(1, $obj->key());

        // renvoit simplement le bon index, même s'il n'y a aucun élément derrière
        $obj->next();
        $this->assertEquals(2, $obj->key());
    }



    /*==================================================================
     * Test : next
     ==================================================================*/
    public function testNext()
    {
        $expected = [new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')];
        $obj = new ListPeriodes($expected);

        $this->assertEquals($expected[0], $obj->current());
        $obj->next();
        $this->assertEquals($expected[1], $obj->current());
        $obj->next();
        $this->assertNull($obj->current());
    }


    /*==================================================================
     * Test : rewind
     ==================================================================*/
    public function providerRewind()
    {
        return [
            "Aucune_Periode" =>
                [ array() ],
            "Une_Periode"   =>
                [ array(new Periode('2015-01-01', '2015-01-02')) ],
            "Deux_Periodes" =>
                [ array(new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')) ],
            "Trois_Periodes" =>
                [ array(
                    new Periode('2015-01-01', '2015-01-02'),
                    new Periode('2016-05-01', '2016-05-02'),
                    new Periode('2017-09-01', '2017-09-02')) ]
        ];
    }

    /**
     * @dataProvider providerRewind
     * @param $array_periodes
     */
    public function testRewindRevenirAZero($array_periodes)
    {
        $obj = new ListPeriodes($array_periodes);

        foreach($obj as $item) {}
        
        $this->assertEquals(iterator_count($obj), $obj->key());
        $obj->rewind();
        $this->assertEquals(0, $obj->key());
    }
    
    /*==================================================================
     * Test : valid
     ==================================================================*/
    public function testValidAvecTableauVide()
    {
        $expected = [];
        $obj = new ListPeriodes($expected);

        $this->assertFalse($obj->valid());
    }

    public function testValidAvecTableau()
    {
        $expected = [new Periode('2015-01-01', '2015-01-02'), new Periode('2016-01-01', '2016-01-02')];
        $obj = new ListPeriodes($expected);

        $this->assertTrue($obj->valid());
        $obj->next();
        $this->assertTrue($obj->valid());
        $obj->next();
        $this->assertFalse($obj->valid());
    }
}
