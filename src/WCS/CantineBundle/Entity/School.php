<?php

namespace WCS\CantineBundle\Entity;

/**
 * School
 */
class School
{
    /**
     * @return string
     */

    public function __toString()
    {
        return $this->name;
    }


    // YAML GENERATED CODE


    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $adress;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var boolean
     */
    private $active_cantine;

    /**
     * @var boolean
     */
    private $active_tap;

    /**
     * @var boolean
     */
    private $active_garderie;

    /**
     * @var boolean
     */
    private $active_voyage;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $divisions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->divisions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->active_cantine = true;
        $this->active_tap = false;
        $this->active_garderie = false;
        $this->active_voyage = false;
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
     * Set name
     *
     * @param string $name
     *
     * @return School
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set adress
     *
     * @param string $adress
     *
     * @return School
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return School
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Set active cantine
     *
     * @param bool $active
     *
     * @return School
     */
    public function setActiveCantine($active)
    {
        $this->active_cantine = $active;

        return $this;
    }

    /**
     * Is cantine active
     *
     * @return bool
     */
    public function getActiveCantine()
    {
        return $this->active_cantine;
    }

    /**
     * Set active tap
     *
     * @param bool $active
     *
     * @return School
     */
    public function setActiveTap($active)
    {
        $this->active_tap = $active;

        return $this;
    }

    /**
     * Is tap active
     *
     * @return bool
     */
    public function getActiveTap()
    {
        return $this->active_tap;
    }

    /**
     * Set active garderie
     *
     * @param bool $active
     *
     * @return School
     */
    public function setActiveGarderie($active)
    {
        $this->active_garderie = $active;

        return $this;
    }

    /**
     * Is garderie active
     *
     * @return bool
     */
    public function getActiveGarderie()
    {
        return $this->active_garderie;
    }

    /**
     * Set active voyage
     *
     * @param bool $active
     *
     * @return School
     */
    public function setActiveVoyage($active)
    {
        $this->active_voyage = $active;

        return $this;
    }

    /**
     * Is voyage active
     *
     * @return bool
     */
    public function getActiveVoyage()
    {
        return $this->active_voyage;
    }

    /**
     * Add division
     *
     * @param \WCS\CantineBundle\Entity\Division $division
     *
     * @return School
     */
    public function addDivision(\WCS\CantineBundle\Entity\Division $division)
    {
        $this->divisions[] = $division;

        return $this;
    }

    /**
     * Remove division
     *
     * @param \WCS\CantineBundle\Entity\Division $division
     */
    public function removeDivision(\WCS\CantineBundle\Entity\Division $division)
    {
        $this->divisions->removeElement($division);
    }

    /**
     * Get divisions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDivisions()
    {
        return $this->divisions;
    }
}
