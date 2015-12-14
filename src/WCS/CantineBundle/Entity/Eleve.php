<?php

namespace WCS\CantineBundle\Entity;

/**
 * Eleve
 */
class Eleve
{
    /**
     * @return string
     */

    public function __toString()
    {
        return $this->nom;
    }

    public function __construct()
    {
        $this->habits = array();
    }

    public static function getHabitDays()
    {
        return array(
            '_monday' => 'lundi',
            '_tuesday' => 'mardi',
            '_thursday' => 'jeudi',
            '_friday' => 'vendredi',
        );
    }

    public static function getHabitDaysLabels()
    {
        $result = array();
        foreach (Eleve::getHabitDays() as $key => $day) {
            $result[$key] = 'Tous les ' . $day . 's';
        }
        return $result;
    }

    public static function getHabitDaysValues()
    {
        return array_keys(self::getHabitDays());
    }


    // GENERATE CODE

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
     * @var \DateTime
     */
    private $dateDeNaissance;

    /**
     * @var boolean
     */
    private $regimeSansPorc;

    /**
     * @var string
     */
    private $allergie;

    /**
     * @var integer
     */
    private $idFoyer;

    /**
     * @var string
     */
    private $Etablissement;

    /**
     * @var boolean
     */
    private $atteste;

    /**
     * @var boolean
     */
    private $autorise;

    /**
     * @var boolean
     */
    private $certifie;

    /**
     * @var string
     */
    private $dates;

    /**
     * @var array
     */
    private $habits;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $user;


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
     * @return Eleve
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
     * @return Eleve
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
     * Set dateDeNaissance
     *
     * @param \DateTime $dateDeNaissance
     *
     * @return Eleve
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
        $this->dateDeNaissance = $dateDeNaissance;

        return $this;
    }

    /**
     * Get dateDeNaissance
     *
     * @return \DateTime
     */
    public function getDateDeNaissance()
    {
        return $this->dateDeNaissance;
    }

    /**
     * Set regimeSansPorc
     *
     * @param boolean $regimeSansPorc
     *
     * @return Eleve
     */
    public function setRegimeSansPorc($regimeSansPorc)
    {
        $this->regimeSansPorc = $regimeSansPorc;

        return $this;
    }

    /**
     * Get regimeSansPorc
     *
     * @return boolean
     */
    public function getRegimeSansPorc()
    {
        return $this->regimeSansPorc;
    }

    /**
     * Set allergie
     *
     * @param string $allergie
     *
     * @return Eleve
     */
    public function setAllergie($allergie)
    {
        $this->allergie = $allergie;

        return $this;
    }

    /**
     * Get allergie
     *
     * @return string
     */
    public function getAllergie()
    {
        return $this->allergie;
    }

    /**
     * Set idFoyer
     *
     * @param integer $idFoyer
     *
     * @return Eleve
     */
    public function setIdFoyer($idFoyer)
    {
        $this->idFoyer = $idFoyer;

        return $this;
    }

    /**
     * Get idFoyer
     *
     * @return integer
     */
    public function getIdFoyer()
    {
        return $this->idFoyer;
    }

    /**
     * Set etablissement
     *
     * @param string $etablissement
     *
     * @return Eleve
     */
    public function setEtablissement($etablissement)
    {
        $this->Etablissement = $etablissement;

        return $this;
    }

    /**
     * Get etablissement
     *
     * @return string
     */
    public function getEtablissement()
    {
        return $this->Etablissement;
    }

    /**
     * Set atteste
     *
     * @param boolean $atteste
     *
     * @return Eleve
     */
    public function setAtteste($atteste)
    {
        $this->atteste = $atteste;

        return $this;
    }

    /**
     * Get atteste
     *
     * @return boolean
     */
    public function getAtteste()
    {
        return $this->atteste;
    }

    /**
     * Set autorise
     *
     * @param boolean $autorise
     *
     * @return Eleve
     */
    public function setAutorise($autorise)
    {
        $this->autorise = $autorise;

        return $this;
    }

    /**
     * Get autorise
     *
     * @return boolean
     */
    public function getAutorise()
    {
        return $this->autorise;
    }

    /**
     * Set certifie
     *
     * @param boolean $certifie
     *
     * @return Eleve
     */
    public function setCertifie($certifie)
    {
        $this->certifie = $certifie;

        return $this;
    }

    /**
     * Get certifie
     *
     * @return boolean
     */
    public function getCertifie()
    {
        return $this->certifie;
    }

    /**
     * Set dates
     *
     * @param string $dates
     *
     * @return Eleve
     */
    public function setDates($dates)
    {
        $this->dates = $dates;

        return $this;
    }

    /**
     * Get dates
     *
     * @return string
     */
    public function getDates()
    {
        return $this->dates;
    }

    /**
     * Set habits
     *
     * @param array $habits
     *
     * @return Eleve
     */
    public function setHabits($habits)
    {
        $this->habits = $habits;

        return $this;
    }

    /**
     * Get habits
     *
     * @return array
     */
    public function getHabits()
    {
        return $this->habits;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     *
     * @return Eleve
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
