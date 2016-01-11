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
     * @var \DateTime
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
     * @param \DateTime $date
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
     * @return \DateTime
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

