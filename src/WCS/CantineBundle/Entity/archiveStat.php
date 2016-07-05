<?php

namespace WCS\CantineBundle\Entity;

/**
 * archiveStat
 */
class archiveStat
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
    private $prenom;

    /**
     * @var string
     */
    private $classe;

    /**
     * @var integer
     */
    private $totalGarderieTap;

    /**
     * @var integer
     */
    private $totalCantine;

    /**
     * @var \DateTime
     */
    private $dateMois;


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
     * @return archiveStat
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return archiveStat
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set classe
     *
     * @param string $classe
     *
     * @return archiveStat
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
     * Set totalGarderieTap
     *
     * @param integer $totalGarderieTap
     *
     * @return archiveStat
     */
    public function setTotalGarderieTap($totalGarderieTap)
    {
        $this->totalGarderieTap = $totalGarderieTap;

        return $this;
    }

    /**
     * Get totalGarderieTap
     *
     * @return integer
     */
    public function getTotalGarderieTap()
    {
        return $this->totalGarderieTap;
    }

    /**
     * Set totalCantine
     *
     * @param integer $totalCantine
     *
     * @return archiveStat
     */
    public function setTotalCantine($totalCantine)
    {
        $this->totalCantine = $totalCantine;

        return $this;
    }

    /**
     * Get totalCantine
     *
     * @return integer
     */
    public function getTotalCantine()
    {
        return $this->totalCantine;
    }

    /**
     * Set dateMois
     *
     * @param \DateTime $dateMois
     *
     * @return archiveStat
     */
    public function setDateMois($dateMois)
    {
        $this->dateMois = $dateMois;

        return $this;
    }

    /**
     * Get dateMois
     *
     * @return \DateTime
     */
    public function getDateMois()
    {
        return $this->dateMois;
    }
}

