<?php

namespace WCS\CantineBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;

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
        return $this->getPrenom().' '.strtoupper($this->getNom());

    }

    public function __construct()
    {
        $this->habits = array();
        $this->voyages = new ArrayCollection();
        $this->regimeSansPorc = false;
    }


    // GENERATED CODE

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
     * @var array
     */
    private $habits;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $lunches;

    /**
     * @var \Application\Sonata\UserBundle\Entity\User
     */
    private $user;

    /**
     * @var \WCS\CantineBundle\Entity\Division
     */
    private $division;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $voyages;


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
     * Add lunch
     *
     * @param \WCS\CantineBundle\Entity\Lunch $lunch
     *
     * @return Eleve
     */
    public function addLunch(\WCS\CantineBundle\Entity\Lunch $lunch)
    {
        $this->lunches[] = $lunch;

        return $this;
    }

    /**
     * Remove lunch
     *
     * @param \WCS\CantineBundle\Entity\Lunch $lunch
     */
    public function removeLunch(\WCS\CantineBundle\Entity\Lunch $lunch)
    {
        $this->lunches->removeElement($lunch);
    }

    /**
     * Get lunches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLunches()
    {
        return $this->lunches;
    }

    /**
     * @param string $dateStr date in format 'Y-m-d'
     * @return bool True if lunches contains the date
     */
    public function isDateInLunches($dateStr)
    {
        /**
         * @var \WCS\CantineBundle\Entity\Lunch $lunch
         */
        foreach($this->lunches as $lunch) {
            if ($lunch->getDate()->format('Y-m-d') === $dateStr) {
                return true;
            }
        }
        return false;
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

    /**
     * Set division
     *
     * @param \WCS\CantineBundle\Entity\Division $division
     *
     * @return Eleve
     */
    public function setDivision(\WCS\CantineBundle\Entity\Division $division = null)
    {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division
     *
     * @return \WCS\CantineBundle\Entity\Division
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Attribut utilisé par les forms types suivants
     * - WCSEmployeeBundle:ActivityEleveType
     * @var \WCS\CantineBundle\Entity\Lunch
     */
    private $date;

    /**
     * Get date
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getDate()
    {
        return $this->date;
    }
    


    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $taps;


    /**
     * Add tap
     *
     * @param \WCS\cantineBundle\Entity\Tap $tap
     *
     * @return Eleve
     */
    public function addTap(\WCS\cantineBundle\Entity\Tap $tap)
    {
        $this->taps[] = $tap;

        return $this;
    }

    /**
     * Remove tap
     *
     * @param \WCS\cantineBundle\Entity\Tap $tap
     */
    public function removeTap(\WCS\cantineBundle\Entity\Tap $tap)
    {
        $this->taps->removeElement($tap);
    }

    /**
     * Get taps
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaps()
    {
        return $this->taps;
    }

    /**
     * Get taps subscribe by parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTapsSubscribeByParents()
    {
        $filtered = [];
        $list = $this->getTaps();
        /**
         * @var \WCS\CantineBundle\Entity\Tap $item
         */
        foreach($list as $item) {
            if ($item->getSubscribedByParent()) {
                $filtered[] = $item;
            }
        }
        return $filtered;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $garderies;


    /**
     * Add gardery
     *
     * @param \WCS\CantineBundle\Entity\Garderie $gardery
     *
     * @return Eleve
     */
    public function addGardery(\WCS\CantineBundle\Entity\Garderie $gardery)
    {
        $this->garderies[] = $gardery;

        return $this;
    }

    /**
     * Remove gardery
     *
     * @param \WCS\CantineBundle\Entity\Garderie $gardery
     */
    public function removeGardery(\WCS\CantineBundle\Entity\Garderie $gardery)
    {
        $this->garderies->removeElement($gardery);
    }

    /**
     * Get garderies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGarderies()
    {
        return $this->garderies;
    }

    /**
     * Get garderies subscribe by parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGarderiesSubscribeByParents()
    {
        $garderies_filtered = [];
        $garderies = $this->getGarderies();
        /**
         * @var \WCS\CantineBundle\Entity\Garderie $item
         */
        foreach($garderies as $item) {
            if ($item->getSubscribedByParent()) {
                $garderies_filtered[] = $item;
            }
        }
        return $garderies_filtered;
    }



    /**
     * Add voyage
     *
     * @param \WCS\CantineBundle\Entity\Voyage $voyage
     *
     * @return Eleve
     */
    public function addVoyage(\WCS\CantineBundle\Entity\Voyage $voyage)
    {
        $this->voyages[] = $voyage;

        return $this;
    }

    /**
     * Remove voyage
     *
     * @param \WCS\CantineBundle\Entity\Voyage $voyage
     */
    public function removeVoyage(\WCS\CantineBundle\Entity\Voyage $voyage)
    {
        $this->voyages->removeElement($voyage);
    }

    /**
     * Get voyages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVoyages()
    {
        return $this->voyages;
    }


    public static function getHabitDays()
    {
        return array(
            'lundi' => 'lundi',
            'mardi' => 'mardi',
            'jeudi' => 'jeudi',
            'vendredi' => 'vendredi',
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

    /**
     * @var bool
     */
    private $canteen_signed = false;

    /**
     * @var bool
     */
    private $tapgarderie_signed = false;

    /**
     * @var bool
     */
    private $voyage_signed = false;


    /**
     * @return boolean
     */
    public function isCanteenSigned()
    {
        return $this->canteen_signed;
    }

    /**
     * @return boolean
     */
    public function isTapgarderieSigned()
    {
        return $this->tapgarderie_signed;
    }

    /**
     * @return boolean
     */
    public function isVoyageSigned()
    {
        return $this->voyage_signed;
    }

    /**
     * @param boolean $canteen_signed
     * @return Eleve
     */
    public function setCanteenSigned($canteen_signed)
    {
        $this->canteen_signed = $canteen_signed;
        return $this;
    }

    /**
     * @param boolean $tapgarderie_signed
     * @return Eleve
     */
    public function setTapgarderieSigned($tapgarderie_signed)
    {
        $this->tapgarderie_signed = $tapgarderie_signed;
        return $this;
    }

    /**
     * @param boolean $voyage_signed
     * @return Eleve
     */
    public function setVoyageSigned($voyage_signed)
    {
        $this->voyage_signed = $voyage_signed;
        return $this;
    }
}
