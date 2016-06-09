<?php

namespace WCS\CantineBundle\Entity;

/**
 * Garderie
 */
class Garderie
{
    const HEURE_MATIN   = ' 08:00:00';
    const HEURE_SOIR    = ' 17:00:00';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \WCS\CantineBundle\Entity\Eleve
     */
    private $eleve;

    /**
     * @var \DateTime
     */
    private $date_heure;

    public function __construct()
    {
        $this->status = '0';
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
     * Set status
     *
     * @param integer $status
     *
     * @return Garderie
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
     * @return Garderie
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


    /**
     * Set dateHeure
     *
     * @param \DateTimeInterface $dateHeure
     *
     * @return Garderie
     */
    public function setDateHeure(\DateTimeInterface $dateHeure)
    {
        $this->date_heure = new \DateTime($dateHeure->format('Y-m-d H:i:s'));

        return $this;
    }

    /**
     * Get dateHeure
     *
     * @return \DateTime
     */
    public function getDateHeure()
    {
        return $this->date_heure;
    }
}
