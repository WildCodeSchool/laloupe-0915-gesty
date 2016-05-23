<?php
namespace WCS\CalendrierBundle\Service\ListPeriodes;


class ListPeriodes implements \Iterator
{
    /**
     * Construit l'objet à partir d'une liste de "Periode"
     *
     * @param $array de WCS\CalendrierBundle\Service\Periode\Periode
     */
    public function __construct(&$array)
    {
        foreach($array as &$item) {
            $this->periodes[] = clone $item;
        }
        $this->current_pos = 0;
    }

    public function get($index)
    {
        return $this->periodes[$index];
    }

    /**
     * @return WCS\CalendrierBundle\Service\Periode\Periode
     */
    public function current()
    {
        return $this->periodes[$this->current_pos];
    }

    public function key()
    {
        return $this->current_pos;
    }

    public function next()
    {
        ++$this->current_pos;
    }

    public function rewind()
    {
        $this->current_pos = 0;
    }

    public function valid()
    {
        return isset($this->periodes[$this->current_pos]);
    }

    /**
     * @var array de WCS\CalendrierBundle\Service\Periode\Periode
     */
    private $periodes;
    private $current_pos;
}