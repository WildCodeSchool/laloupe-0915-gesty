<?php

namespace WCS\CantineBundle\Entity;

/**
 * Etablissement
 */
class Etablissement
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Etablissement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
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
}
