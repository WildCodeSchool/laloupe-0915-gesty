<?php
namespace WCS\CantineBundle\Form\FormEntity;


use WCS\CantineBundle\Entity\Eleve;

class EleveFormEntity extends Eleve
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
     * @return $this
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
