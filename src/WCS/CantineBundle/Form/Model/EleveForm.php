<?php

namespace WCS\CantineBundle\Form\Model;
use WCS\CantineBundle\Entity\Eleve;

/**
 * EleveForm
 */
class EleveForm extends Eleve
{
    /**
     * @return string
     */

    public function __toString()
    {
        return $this->getNom();
    }

   /**
     * @var boolean
     */
    private $certifie;

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
}
