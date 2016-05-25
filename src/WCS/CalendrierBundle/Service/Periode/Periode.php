<?php
/*===========================================================================

    Créé une période non modifiable.

===========================================================================*/
namespace WCS\CalendrierBundle\Service\Periode;
use WCS\CalendrierBundle\Service\Periode\Exception\IllSortedDatesException;
use WCS\CalendrierBundle\Service\Periode\Exception\InvalidArgumentException;
use WCS\CalendrierBundle\Service\Periode\Exception\InvalidDateException;
use WCS\CalendrierBundle\Service\Periode\Exception\PeriodeException;


class Periode
{

    /*--------------------------------------------------------------------------------
        METHODES PUBLIQUES
    --------------------------------------------------------------------------------*/

    /**
     * Periode constructor.
     *
     * Créé une période fixe avec son type et optionnellement une description.
     *
     * @param Mixed   $dateDebut
     *                Date de début de la période
     *                type de cette variable :
     *                soit :
     *                - string au format "Y-m-d"
     *                - \DateTime
     *                - \DateTimeImmutable
     *
     * @param Mixed    $dateFin
     *                Date de fin de la période
     *                type de cette variable :
     *                soit :
     *                - string au format "Y-m-d"
     *                - \DateTime
     *                - \DateTimeImmutable
     *
     * @param string $description   $description
     *                              Facultatif, Description libre de la période
     *
     * @throws InvalidArgumentException
     * @throws InvalidDateException
     * @throws IllSortedDatesException
     */
    public function __construct(
                        $dateDebut,
                        $dateFin,
                        $description=''
                    )
    {
        $this->dateDebut    = $this->toDateTimeImmutable($dateDebut, PeriodeException::DATE_DEBUT);
        $this->dateFin      = $this->toDateTimeImmutable($dateFin, PeriodeException::DATE_FIN);
        $this->description  = $description;

        // s'assure que les dates sont ordonnées correctement
        if ($this->dateDebut > $this->dateFin) {
            throw new IllSortedDatesException( $this->dateDebut->format('Y-m-d'), $this->dateFin->format('Y-m-d') );
        }
    }


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
     * @param $date
     *
     * une date de type :
     *  - string au format "Y-m-d'
     *  - DateTime
     *  - DateTimeImmutable (pas de conversion dans ce cas)
     *
     * @return bool retourne true si la date est bien incluse dans la période
     * @throws InvalidArgumentException
     * @throws InvalidDateException
     */
    public function isDateIncluded($date)
    {
        $dateTmp = $this->toDateTimeImmutable($date, PeriodeException::DATE);
        $dateStr = $dateTmp->format('Y-m-d');
        return  $dateStr >= $this->dateDebut->format('Y-m-d') &&
                $dateStr <= $this->dateFin->format('Y-m-d');
    }



    /*--------------------------------------------------------------------------------
      METHODES PRIVEES
    --------------------------------------------------------------------------------*/

    /**
     * Convertit une date de type :
     *  - string au format "Y-m-d'
     *  - DateTime
     *  - DateTimeImmutable (pas de conversion dans ce cas)
     *
     * en un DateTimeImmutable
     *
     * @param $date
     * @return \DateTimeImmutable|null
     * @throws InvalidArgumentException
     * @throws InvalidDateException
     */
    private function toDateTimeImmutable($date, $exceptionCode)
    {
        $dateImmutable = null;
        if ($date instanceof \DateTimeImmutable) {
            $dateImmutable = $date;
        }
        else if ($date instanceof \DateTime) {
            $dateImmutable = new \DateTimeImmutable($date->format('Y-m-d'));
        }
        else if (is_string($date)) {

            $dateToCheck = \DateTime::createFromFormat('Y-m-d', $date);
            if (!$dateToCheck) {
                throw new InvalidArgumentException($exceptionCode);
            }

            if ($dateToCheck->format('Y-m-d')!=$date) {
                throw new InvalidDateException($exceptionCode, $date);
            }

            $dateImmutable = new \DateTimeImmutable($date);
        }
        else {
            throw new InvalidArgumentException($exceptionCode);
        }

        return $dateImmutable;
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