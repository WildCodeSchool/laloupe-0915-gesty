<?php

namespace WCS\CantineBundle\Form\Model;
use WCS\CantineBundle\Entity\Eleve;

/**
 * EleveForm
 */
class CantineFormEntity extends Eleve
{
    /**
     * @var boolean
     */
    private $atteste;

    /**
     * @var boolean
     */
    private $autorise;

    /**
     * @var boolean
     */
    private $certifie;

    /**
     * @var string
     */
    private $dates;

    /**
     * Set atteste
     *
     * @param boolean $atteste
     *
     * @return EleveNew
     */
    public function setAtteste($atteste)
    {
        $this->atteste = $atteste;

        return $this;
    }

    /**
     * Get atteste
     *
     * @return boolean
     */
    public function getAtteste()
    {
        return $this->atteste;
    }

    /**
     * Set autorise
     *
     * @param boolean $autorise
     *
     * @return EleveNew
     */
    public function setAutorise($autorise)
    {
        $this->autorise = $autorise;

        return $this;
    }

    /**
     * Get autorise
     *
     * @return boolean
     */
    public function getAutorise()
    {
        return $this->autorise;
    }

    /**
     * Set certifie
     *
     * @param boolean $certifie
     *
     * @return EleveNew
     */
    public function setCertifie($certifie)
    {
        $this->certifie = $certifie;

        return $this;
    }

    /**
     * Get certifie
     *
     * @return boolean
     */
    public function getCertifie()
    {
        return $this->certifie;
    }

    /**
     * Set dates
     *
     * @param string $dates
     *
     * @return EleveNew
     */
    public function setDates($dates)
    {
        $this->dates = $dates;

        return $this;
    }

    /**
     * Get dates
     *
     * @return string
     */
    public function getDates()
    {
        return $this->dates;
    }

}
