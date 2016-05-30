<?php
namespace WCS\CalendrierBundle\Service\ListPeriodes;
use WCS\CalendrierBundle\Service\Periode\Periode;

class ListPeriodes implements \Iterator
{
    /**
     * @param $index
     * @return mixed
     */
    public function get($index)
    {
        if ($index >= count($this->periodes) || $index < 0 || !is_numeric($index) ) {
            return null;
        }
        return $this->periodes[$index];
    }

    /**
     * La documentation PHP indique que current est appelé après l'appel
     * de valid(), mais le comportement n'est correct que si on utilise
     * l'iterateur dans un foreach.
     * Neanmoins, les méthodes étant publiques, elles peuvent être
     * appelées indépendamment. Ici on doit donc s'assurer que la clef est disponible
     * @return WCS\CalendrierBundle\Service\Periode\Periode
     */
    public function current()
    {
        if (!$this->valid()) {
            return null;
        }
        return $this->periodes[$this->current_pos];
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->current_pos;
    }

    /**
     *
     */
    public function next()
    {
        ++$this->current_pos;
    }

    /**
     *
     */
    public function rewind()
    {
        $this->current_pos = 0;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->periodes[$this->current_pos]);
    }


    /**
     * Construit l'objet à partir d'une liste de "Periode"
     *
     * @param $array d'instances de WCS\CalendrierBundle\Service\Periode\Periode
     * @throws \Exception si un des éléments du tableau n'est pas une instance de Periode
     */
    public function __construct(&$array)
    {
        $failedItem = null;

        try {
            $this->periodes = array();
            foreach($array as &$item) {
                if (!$item instanceof Periode) {
                    $failedItem = $item;
                    throw new \Exception('');
                }
                $this->periodes[] = clone $item;
            }
            $this->current_pos = 0;
        }
        catch(\Exception $e) {
            $type_found = gettype($failedItem);
            if ($type_found == "object") {
                $type_found = get_class($failedItem);
            }
            throw new \Exception(
                "Les éléments du tableau doivent toutes être des instances de Periode. Type de l'élément incriminé  : $type_found"
            );
        }
    }

    /**
     * @var array de WCS\CalendrierBundle\Service\Periode\Periode
     */
    private $periodes;
    private $current_pos;
}
