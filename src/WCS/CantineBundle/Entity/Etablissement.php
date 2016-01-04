<?php

namespace WCS\CantineBundle\Entity;

/**
 * Etablissement
 */
class Etablissement
{
    public function __toString(){
        return $this->instituteur.' - '.$this->ecole. ' - ' .$this->classe;
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $classe;

    /**
     * @var string
     */
    private $instituteur;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set classe
     *
     * @param string $classe
     *
     * @return Etablissement
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set instituteur
     *
     * @param string $instituteur
     *
     * @return Etablissement
     */
    public function setInstituteur($instituteur)
    {
        $this->instituteur = $instituteur;

        return $this;
    }

    /**
     * Get instituteur
     *
     * @return string
     */
    public function getInstituteur()
    {
        return $this->instituteur;
    }
    /**
     * @var string
     */
    private $ecole;


    /**
     * Set ecole
     *
     * @param string $ecole
     *
     * @return Etablissement
     */
    public function setEcole($ecole)
    {
        $this->ecole = $ecole;

        return $this;
    }

    /**
     * Get ecole
     *
     * @return string
     */
    public function getEcole()
    {
        return $this->ecole;
    }

}
