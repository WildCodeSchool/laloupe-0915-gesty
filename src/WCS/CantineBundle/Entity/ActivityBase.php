<?php

namespace WCS\CantineBundle\Entity;

/**
 * TacheBase
 */
class ActivityBase
{
    const STATUS_REGISTERED_BUT_ABSENT  = '0';
    const STATUS_NOT_REGISTERED         = '1';
    const STATUS_REGISTERED_AND_PRESENT = '2';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var integer
     */
    protected $status;

    /**
     * @var \WCS\CantineBundle\Entity\Eleve
     */
    protected $eleve;

    /**
     * @var boolean
     */
    protected $subscribed_by_parent = false;

    /**
     * ActivityBase constructor.
     */
    public function __construct()
    {
        $this->status = self::STATUS_REGISTERED_BUT_ABSENT;
        $this->date = new \DateTime();
    }

    /**
     * Utile pour sonata
     * @return string renvoit le __toString de l'eleve
     */
    public function __toString()
    {
        return (string) $this->getEleve(). " inscrit le ".$this->getDate()->format('d/m/Y');

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
     * Set date
     *
     * @param \DateTimeInterface $date
     *
     * @return Tap
     */
    public function setDate(\DateTimeInterface $date)
    {
        $this->date = new \DateTime($date->format('Y-m-d H:i:s'));

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
     * @return Tap
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
     * @return Tap
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
     * @return boolean
     */
    public function getSubscribedByParent()
    {
        return $this->subscribed_by_parent;
    }

    /**
     * @param boolean $subscribed_by_parent
     * @return $this
     */
    public function setSubscribedByParent($subscribed_by_parent)
    {
        $this->subscribed_by_parent = $subscribed_by_parent;
        return $this;
    }

}
