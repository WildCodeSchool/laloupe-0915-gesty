<?php

namespace Gesty\GestyBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Formulaire
 */
class Formulaire
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $civilite;

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
    private $adresse;

    /**
     * @var integer
     */
    private $codePostal;

    /**
     * @var string
     */
    private $commune;

    /**
     * @var integer
     */
    private $telephone;

    /**
     * @var integer
     */
    private $telephoneSecondaire;

    /**
     * @var string
     */
    private $caf;

    /**
     * @var integer
     */
    private $modeDePaiement;

    /**
     * @var string
     */
    private $numeroIban;

    /**
     * @var boolean
     * @ORM\Column(name="mandatActif", nullable = false)
     */
    private $mandatActif;


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
     * Set civilite
     *
     * @param integer $civilite
     *
     * @return Formulaire
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return integer
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Formulaire
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
     * @return Formulaire
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Formulaire
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Formulaire
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set commune
     *
     * @param string $commune
     *
     * @return Formulaire
     */
    public function setCommune($commune)
    {
        $this->commune = $commune;

        return $this;
    }

    /**
     * Get commune
     *
     * @return string
     */
    public function getCommune()
    {
        return $this->commune;
    }

    /**
     * Set telephone
     *
     * @param integer $telephone
     *
     * @return Formulaire
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return integer
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set telephoneSecondaire
     *
     * @param integer $telephoneSecondaire
     *
     * @return Formulaire
     */
    public function setTelephoneSecondaire($telephoneSecondaire)
    {
        $this->telephoneSecondaire = $telephoneSecondaire;

        return $this;
    }

    /**
     * Get telephoneSecondaire
     *
     * @return integer
     */
    public function getTelephoneSecondaire()
    {
        return $this->telephoneSecondaire;
    }

    /**
     * Set caf
     *
     * @param string $caf
     *
     * @return Formulaire
     */
    public function setCaf($caf)
    {
        $this->caf = $caf;

        return $this;
    }

    /**
     * Get caf
     *
     * @return string
     */
    public function getCaf()
    {
        return $this->caf;
    }

    /**
     * Set modeDePaiement
     *
     * @param integer $modeDePaiement
     *
     * @return Formulaire
     */
    public function setModeDePaiement($modeDePaiement)
    {
        $this->modeDePaiement = $modeDePaiement;

        return $this;
    }

    /**
     * Get modeDePaiement
     *
     * @return integer
     */
    public function getModeDePaiement()
    {
        return $this->modeDePaiement;
    }

    /**
     * Set numeroIban
     *
     * @param string $numeroIban
     *
     * @return Formulaire
     */
    public function setNumeroIban($numeroIban)
    {
        $this->numeroIban = $numeroIban;

        return $this;
    }

    /**
     * Get numeroIban
     *
     * @return string
     */
    public function getNumeroIban()
    {
        return $this->numeroIban;
    }

    /**
     * Set mandatActif
     *
     * @param boolean $mandatActif
     *
     * @return Formulaire
     */
    public function setMandatActif($mandatActif)
    {
        $this->mandatActif = $mandatActif;

        return $this;
    }

    /**
     * Get mandatActif
     *
     * @return boolean
     */
    public function getMandatActif()
    {
        return $this->mandatActif;
    }
}

