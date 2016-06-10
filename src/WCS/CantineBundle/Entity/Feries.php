<?php

namespace WCS\CantineBundle\Entity;

/**
 * Feries
 */
class Feries
{
    // fixed days off (date format '-m-d')
    const JOUR_AN       = '-01-01';
    const FETE_TRAVAIL  = '-05-01';
    const HUIT_MAI      = '-05-08';
    const FETE_NATIONAL = '-07-14';
    const ASSOMPTION    = '-08-15';
    const TOUSSAINT     = '-11-01';
    const ARMISTICE     = '-11-11';
    const NOEL          = '-12-25';

    // Generated code
   
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $annee;

    /**
     * @var \DateTime
     */
    private $paques;

    /**
     * @var \DateTime
     */
    private $ascension;

    /**
     * @var \DateTime
     */
    private $vendredi_ascension;


    /**
     * @var \DateTime
     */
    private $pentecote;


    /**
     * @var \DateTime
     */
    private $jour_an;
    /**
     * @var \DateTime
     */
    private $fete_travail;
    /**
     * @var \DateTime
     */
    private $huit_mai;
    /**
     * @var \DateTime
     */
    private $fete_national;
    /**
     * @var \DateTime
     */
    private $assomption;
    /**
     * @var \DateTime
     */
    private $toussaint;
    /**
     * @var \DateTime
     */
    private $armistice;
    /**
     * @var \DateTime
     */
    private $noel;

    /**
     * @return \DateTime
     */
    public function getArmistice()
    {
        return $this->armistice;
    }

    /**
     * @return \DateTime
     */
    public function getAssomption()
    {
        return $this->assomption;
    }

    /**
     * @return \DateTime
     */
    public function getFeteNational()
    {
        return $this->fete_national;
    }

    /**
     * @return \DateTime
     */
    public function getFeteTravail()
    {
        return $this->fete_travail;
    }

    /**
     * @return \DateTime
     */
    public function getHuitMai()
    {
        return $this->huit_mai;
    }

    /**
     * @return \DateTime
     */
    public function getJourAn()
    {
        return $this->jour_an;
    }

    /**
     * @return \DateTime
     */
    public function getNoel()
    {
        return $this->noel;
    }

    /**
     * @return \DateTime
     */
    public function getToussaint()
    {
        return $this->toussaint;
    }

    public function onPreChangeFixedDays()
    {
        $this->jour_an          = new \DateTime($this->annee.self::JOUR_AN);
        $this->fete_travail     = new \DateTime($this->annee.self::FETE_TRAVAIL);
        $this->huit_mai         = new \DateTime($this->annee.self::HUIT_MAI);
        $this->fete_national    = new \DateTime($this->annee.self::FETE_NATIONAL);
        $this->assomption       = new \DateTime($this->annee.self::ASSOMPTION);
        $this->toussaint        = new \DateTime($this->annee.self::TOUSSAINT);
        $this->armistice        = new \DateTime($this->annee.self::ARMISTICE);
        $this->noel             = new \DateTime($this->annee.self::NOEL);

    }

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
     * Set annee
     *
     * @param string $annee
     *
     * @return Feries
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return string
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set paques
     *
     * @param \DateTime $paques
     *
     * @return Feries
     */
    public function setPaques($paques)
    {
        $this->paques = $paques;

        return $this;
    }

    /**
     * Get paques
     *
     * @return \DateTime
     */
    public function getPaques()
    {
        return $this->paques;
    }

    /**
     * Set ascension
     *
     * @param \DateTime $ascension
     *
     * @return Feries
     */
    public function setAscension($ascension)
    {
        $this->ascension = $ascension;

        return $this;
    }

    /**
     * Get ascension
     *
     * @return \DateTime
     */
    public function getAscension()
    {
        return $this->ascension;
    }

    /**
     * Set vendrediAscension
     *
     * @param \DateTime $vendrediAscension
     *
     * @return Feries
     */
    public function setVendrediAscension($vendrediAscension)
    {
        $this->vendredi_ascension = $vendrediAscension;

        return $this;
    }

    /**
     * Get vendrediAscension
     *
     * @return \DateTime
     */
    public function getVendrediAscension()
    {
        return $this->vendredi_ascension;
    }

    /**
     * Set pentecote
     *
     * @param \DateTime $pentecote
     *
     * @return Feries
     */
    public function setPentecote($pentecote)
    {
        $this->pentecote = $pentecote;

        return $this;
    }

    /**
     * Get pentecote
     *
     * @return \DateTime
     */
    public function getPentecote()
    {
        return $this->pentecote;
    }
 }
