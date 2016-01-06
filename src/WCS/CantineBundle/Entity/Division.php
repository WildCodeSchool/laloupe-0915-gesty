<?php

namespace WCS\CantineBundle\Entity;

/**
 * Division
 */
class Division
{
    public function __toString(){
        return ucwords($this->headTeacher).' - '.$this->school. ' - ' .$this->grade;
        //return '';
    }


    // YAML GENERATED CODE

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $grade;

    /**
     * @var string
     */
    private $headTeacher;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $eleves;

    /**
     * @var \WCS\CantineBundle\Entity\School
     */
    private $school;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eleves = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set grade
     *
     * @param string $grade
     *
     * @return Division
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set headTeacher
     *
     * @param string $headTeacher
     *
     * @return Division
     */
    public function setHeadTeacher($headTeacher)
    {
        $this->headTeacher = $headTeacher;

        return $this;
    }

    /**
     * Get headTeacher
     *
     * @return string
     */
    public function getHeadTeacher()
    {
        return $this->headTeacher;
    }

    /**
     * Add elefe
     *
     * @param \WCS\CantineBundle\Entity\Eleve $elefe
     *
     * @return Division
     */
    public function addElefe(\WCS\CantineBundle\Entity\Eleve $elefe)
    {
        $this->eleves[] = $elefe;

        return $this;
    }

    /**
     * Remove elefe
     *
     * @param \WCS\CantineBundle\Entity\Eleve $elefe
     */
    public function removeElefe(\WCS\CantineBundle\Entity\Eleve $elefe)
    {
        $this->eleves->removeElement($elefe);
    }

    /**
     * Get eleves
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEleves()
    {
        return $this->eleves;
    }

    /**
     * Set school
     *
     * @param \WCS\CantineBundle\Entity\School $school
     *
     * @return Division
     */
    public function setSchool(\WCS\CantineBundle\Entity\School $school = null)
    {
        $this->school = $school;

        return $this;
    }

    /**
     * Get school
     *
     * @return \WCS\CantineBundle\Entity\School
     */
    public function getSchool()
    {
        return $this->school;
    }
}
