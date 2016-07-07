<?php

namespace WCS\CantineBundle\Entity;

/**
 * ArchiveStat
 */
class ArchiveStat
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nom;

    /**
     * @var string
     */
    private $prenom;

    /**
     * @var string
     */
    private $classe;

    /**
     * @var integer
     */
    private $totalGarderieTap;

    /**
     * @var integer
     */
    private $totalCantine;

    /**
     * @var \DateTime
     */
    private $dateMois;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return archiveStat
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return archiveStat
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set classe
     *
     * @param string $classe
     *
     * @return archiveStat
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set totalGarderieTap
     *
     * @param integer $totalGarderieTap
     *
     * @return archiveStat
     */
    public function setTotalGarderieTap($totalGarderieTap)
    {
        $this->totalGarderieTap = $totalGarderieTap;

        return $this;
    }

    /**
     * Get totalGarderieTap
     *
     * @return integer
     */
    public function getTotalGarderieTap()
    {
        return $this->totalGarderieTap;
    }

    /**
     * Set totalCantine
     *
     * @param integer $totalCantine
     *
     * @return archiveStat
     */
    public function setTotalCantine($totalCantine)
    {
        $this->totalCantine = $totalCantine;

        return $this;
    }

    /**
     * Get totalCantine
     *
     * @return integer
     */
    public function getTotalCantine()
    {
        return $this->totalCantine;
    }

    /**
     * Set dateMois
     *
     * @param \DateTime $dateMois
     *
     * @return archiveStat
     */
    public function setDateMois($dateMois)
    {
        $this->dateMois = $dateMois;

        return $this;
    }

    /**
     * Get dateMois
     *
     * @return \DateTime
     */
    public function getDateMois()
    {
        return $this->dateMois;
    }
    /**
     * @var integer
     */
    private $eleve_id_backup;

    /**
     * @var integer
     */
    private $parent_user_id_backup;

    /**
     * @var string
     */
    private $parent_nom = '';

    /**
     * @var string
     */
    private $parent_prenom = '';

    /**
     * @var string
     */
    private $parent_email;

    /**
     * @var integer
     */
    private $ecole_school_id_backup;

    /**
     * @var string
     */
    private $ecole;

    /**
     * @var string
     */
    private $instit = '';


    /**
     * Set eleveIdBackup
     *
     * @param integer $eleveIdBackup
     *
     * @return ArchiveStat
     */
    public function setEleveIdBackup($eleveIdBackup)
    {
        $this->eleve_id_backup = $eleveIdBackup;

        return $this;
    }

    /**
     * Get eleveIdBackup
     *
     * @return integer
     */
    public function getEleveIdBackup()
    {
        return $this->eleve_id_backup;
    }

    /**
     * Set parentUserIdBackup
     *
     * @param integer $parentUserIdBackup
     *
     * @return ArchiveStat
     */
    public function setParentUserIdBackup($parentUserIdBackup)
    {
        $this->parent_user_id_backup = $parentUserIdBackup;

        return $this;
    }

    /**
     * Get parentUserIdBackup
     *
     * @return integer
     */
    public function getParentUserIdBackup()
    {
        return $this->parent_user_id_backup;
    }

    /**
     * Set parentNom
     *
     * @param string $parentNom
     *
     * @return ArchiveStat
     */
    public function setParentNom($parentNom)
    {
        $this->parent_nom = is_null($parentNom)?'':$parentNom;

        return $this;
    }

    /**
     * Get parentNom
     *
     * @return string
     */
    public function getParentNom()
    {
        return $this->parent_nom;
    }

    /**
     * Set parentPrenom
     *
     * @param string $parentPrenom
     *
     * @return ArchiveStat
     */
    public function setParentPrenom($parentPrenom)
    {
        $this->parent_prenom = is_null($parentPrenom)?'':$parentPrenom;

        return $this;
    }

    /**
     * Get parentPrenom
     *
     * @return string
     */
    public function getParentPrenom()
    {
        return $this->parent_prenom;
    }

    /**
     * Set parentEmail
     *
     * @param string $parentEmail
     *
     * @return ArchiveStat
     */
    public function setParentEmail($parentEmail)
    {
        $this->parent_email = $parentEmail;

        return $this;
    }

    /**
     * Get parentEmail
     *
     * @return string
     */
    public function getParentEmail()
    {
        return $this->parent_email;
    }

    /**
     * Set ecoleSchoolIdBackup
     *
     * @param integer $ecoleSchoolIdBackup
     *
     * @return ArchiveStat
     */
    public function setEcoleSchoolIdBackup($ecoleSchoolIdBackup)
    {
        $this->ecole_school_id_backup = $ecoleSchoolIdBackup;

        return $this;
    }

    /**
     * Get ecoleSchoolIdBackup
     *
     * @return integer
     */
    public function getEcoleSchoolIdBackup()
    {
        return $this->ecole_school_id_backup;
    }

    /**
     * Set ecole
     *
     * @param string $ecole
     *
     * @return ArchiveStat
     */
    public function setEcole($ecole)
    {
        $this->ecole = $ecole;

        return $this;
    }

    /**
     * Get ecole
     *
     * @return string
     */
    public function getEcole()
    {
        return $this->ecole;
    }


    /**
     * Set instit
     *
     * @param string $instit
     *
     * @return ArchiveStat
     */
    public function setInstit($instit)
    {
        $this->instit = is_null($instit)?'':$instit;

        return $this;
    }

    /**
     * Get instit
     *
     * @return string
     */
    public function getInstit()
    {
        return $this->instit;
    }
    /**
     * @var integer
     */
    private $classe_division_id_backup;


    /**
     * Set classeDivisionIdBackup
     *
     * @param integer $classeDivisionIdBackup
     *
     * @return ArchiveStat
     */
    public function setClasseDivisionIdBackup($classeDivisionIdBackup)
    {
        $this->classe_division_id_backup = $classeDivisionIdBackup;

        return $this;
    }

    /**
     * Get classeDivisionIdBackup
     *
     * @return integer
     */
    public function getClasseDivisionIdBackup()
    {
        return $this->classe_division_id_backup;
    }
}
