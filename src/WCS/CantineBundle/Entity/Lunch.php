<?php

namespace WCS\CantineBundle\Entity;

/**
 * Lunch
 */
class Lunch
{

    // GENERATED CODE
    
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \WCS\CantineBundle\Entity\Eleve
     */
    private $eleve;


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
     * Set status
     *
     * @param integer $status
     *
     * @return Lunch
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set eleve
     *
     * @param \WCS\CantineBundle\Entity\Eleve $eleve
     *
     * @return Lunch
     */
    public function setEleve(\WCS\CantineBundle\Entity\Eleve $eleve = null)
    {
        $this->eleve = $eleve;

        return $this;
    }

    /**
     * Get eleve
     *
     * @return \WCS\CantineBundle\Entity\Eleve
     */
    public function getEleve()
    {
        return $this->eleve;
    }
}
