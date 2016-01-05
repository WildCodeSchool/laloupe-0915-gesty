<?php

namespace WCS\CantineBundle\Entity;

/**
 * School
 */
class School
{


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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $divisions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->divisions = new \Doctrine\Common\Collections\ArrayCollection();
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
