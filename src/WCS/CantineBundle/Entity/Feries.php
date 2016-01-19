<?php

namespace WCS\CantineBundle\Entity;

/**
 * Feries
 */
class Feries
{
    /*public function __toString()
    {
        return $this->annee;

    }*/

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
    private $pentecote;


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
