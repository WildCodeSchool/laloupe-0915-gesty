<?php
/*===========================================================================

    Créé une période non modifiable.

===========================================================================*/
namespace WCS\CalendrierBundle\Services;


class CalendrierPeriode
{
    /*--------------------------------------------------------------------------------
        CONSTRUCTEUR
    --------------------------------------------------------------------------------*/
    /**
     * CalendrierPeriode constructor.
     *
     * Créé une période fixe avec son type et optionnellement une description.
     *
     * @param \DateTimeInterface    $dateDebut  Date de début de la période
     * @param \DateTimeInterface    $dateFin    Date de fin de la période
     * @param string $description   Description libre de la période
     */
    public function __construct(
                        \DateTimeInterface $dateDebut,
                        \DateTimeInterface $dateFin,
                        $description=''
                    )
    {
        // vérifie le type de date debut (on autorise tout objet héritant de datetimeinterface)

        if ($dateDebut instanceof \DateTimeImmutable) {
            $dateDebutImmutable = $dateDebut;
        }
        else {
            $dateDebutImmutable = \DateTimeImmutable::createFromMutable($dateDebut);
        }

        // vérifie le type de date fin

        if ($dateDebut instanceof \DateTimeImmutable) {
            $dateFinImmutable = $dateFin;
        }
        else {
            $dateFinImmutable = \DateTimeImmutable::createFromMutable($dateFin);
        }

        // assigne les attributs

        $this->dateDebut    = $dateDebutImmutable;
        $this->dateFin      = $dateFinImmutable;
        $this->description  = $description;
    }


    /*--------------------------------------------------------------------------------
        METHODES
    --------------------------------------------------------------------------------*/

    /**
     * @return \DateTimeImmutable renvoit la date de début, non modifiable
     */
    public function getDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @return \DateTimeImmutable renvoit la date de fin, non modifiable
     */
    public function getFin()
    {
        return $this->dateFin;
    }

    /**
     * @return string renvoit la description libre
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * vérifie si une date (jour/mois/annee) est bien incluse dans la période
     * (vrai si la date vaut le premier ou le dernier jour de la période)
     *
     * @param \DateTime $date
     * @return bool retourne true si la date est bien incluse dans la période
     */
    public function isDateIncluded(\DateTime $date)
    {
        $dateStr = $date->format('Y-m-d');
        return  $dateStr >= $this->dateDebut->format('Y-m-d') &&
                $dateStr <= $this->dateFin->format('Y-m-d');
    }



    /*--------------------------------------------------------------------------------
       ATTRIBUTS
   --------------------------------------------------------------------------------*/

    /**
     * @var \DateTimeImmutable date de début non modifiable de la période
     */
    private $dateDebut;

    /**
     * @var \DateTimeImmutable date de fin non modifiable de la période
     */
    private $dateFin;

    /**
     * @var string une description associée à la période
     */
    private $description;
}