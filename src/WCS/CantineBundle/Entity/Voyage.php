<?php

namespace WCS\CantineBundle\Entity;

/**
 * Voyage
 */
class Voyage
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var \DateTime
     */
    private $date_debut;

    /**
     * @var \DateTime
     */
    private $date_fin;

    /**
     * @var boolean
     */
    private $estAnnule;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $eleves;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $divisions;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eleves = new \Doctrine\Common\Collections\ArrayCollection();
        $this->divisions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date_debut = new \DateTime();
        $this->date_fin = new  \DateTime();
        $this->estAnnule = FALSE;
    }

    /**
     *
     * @return string l'intitulé du voyage et les dates
     */
    public function __toString()
    {
        $str_date = "";

        $interval = $this->date_debut->diff( $this->date_fin );

        if ($interval->days<1) {
            $str_date = "le ".$this->date_debut->format('d/m/Y').
                ", ".$this->date_debut->format('H:i').
                " / ".$this->date_fin->format('H:i');
        }
        else {
            $str_date = "du ".$this->date_debut->format('d/m/Y').
                " à ".$this->date_debut->format('H:i').
                " au ".$this->date_fin->format('d/m/Y').
                " à ".$this->date_fin->format('H:i');

        }

        return $this->libelle. ' (' .$str_date. ')';
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Voyage
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Voyage
     */
    public function setDateDebut($dateDebut)
    {
        $this->date_debut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Voyage
     */
    public function setDateFin($dateFin)
    {
        $this->date_fin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * Add eleve
     *
     * @param \WCS\CantineBundle\Entity\Eleve $eleve
     *
     * @return Voyage
     */
    public function addEleve(\WCS\CantineBundle\Entity\Eleve $eleve)
    {
        $this->eleves[] = $eleve;

        return $this;
    }

    /**
     * Remove eleve
     *
     * @param \WCS\CantineBundle\Entity\Eleve $eleve
     */
    public function removeEleve(\WCS\CantineBundle\Entity\Eleve $eleve)
    {
        $this->eleves->removeElement($eleve);
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
     * Set estAnnule
     *
     * @param boolean $estAnnule
     *
     * @return Voyage
     */
    public function setEstAnnule($estAnnule)
    {
        $this->estAnnule = $estAnnule;

        return $this;
    }

    /**
     * Get estAnnule
     *
     * @return boolean
     */
    public function getEstAnnule()
    {
        return $this->estAnnule;
    }

    /**
     * Add division
     *
     * @param \WCS\CantineBundle\Entity\Division $division
     *
     * @return Voyage
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

    /**
     * Add elefe
     *
     * @param \WCS\CantineBundle\Entity\Eleve $elefe
     *
     * @return Voyage
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
}
