<?php

namespace WCS\CantineBundle\Entity;

/**
 * Lunch
 */
class Lunch
{
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $inscrits;

    /**
     * @var string
     */
    private $presents;

    /**
     * @var string
     */
    private $noninscrits;

    /**
     * @var string
     */
    private $absents;

    /**
     * @var string
     */
    private $commentaires;


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
     * Set date
     *
     * @param string $date
     *
     * @return Lunch
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set inscrits
     *
     * @param string $inscrits
     *
     * @return Lunch
     */
    public function setInscrits($inscrits)
    {
        $this->inscrits = $inscrits;

        return $this;
    }

    /**
     * Get inscrits
     *
     * @return string
     */
    public function getInscrits()
    {
        return $this->inscrits;
    }

    /**
     * Set presents
     *
     * @param string $presents
     *
     * @return Lunch
     */
    public function setPresents($presents)
    {
        $this->presents = $presents;

        return $this;
    }

    /**
     * Get presents
     *
     * @return string
     */
    public function getPresents()
    {
        return $this->presents;
    }

    /**
     * Set noninscrits
     *
     * @param string $noninscrits
     *
     * @return Lunch
     */
    public function setNoninscrits($noninscrits)
    {
        $this->noninscrits = $noninscrits;

        return $this;
    }

    /**
     * Get noninscrits
     *
     * @return string
     */
    public function getNoninscrits()
    {
        return $this->noninscrits;
    }

    /**
     * Set absents
     *
     * @param string $absents
     *
     * @return Lunch
     */
    public function setAbsents($absents)
    {
        $this->absents = $absents;

        return $this;
    }

    /**
     * Get absents
     *
     * @return string
     */
    public function getAbsents()
    {
        return $this->absents;
    }

    /**
     * Set commentaires
     *
     * @param string $commentaires
     *
     * @return Lunch
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }
}
