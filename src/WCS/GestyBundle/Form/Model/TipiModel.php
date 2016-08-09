<?php


namespace WCS\GestyBundle\Form\Model;

class TipiModel {
    
    private $date;
    private $montant;
    private $mel;
    private  $refdet;

    /**
     * @return mixed
     */
    public function getRefdet()
    {
        return $this->refdet;
    }

    /**
     * @param mixed $refdet
     */
    public function setRefdet($refdet)
    {
        $this->refdet = $refdet;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * @param mixed $montant
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    }

    /**
     * @return mixed
     */
    public function getMel()
    {
        return $this->mel;
    }

    /**
     * @param mixed $mel
     */
    public function setMel($mel)
    {
        $this->mel = $mel;
    }
    
    
}