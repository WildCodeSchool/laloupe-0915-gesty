<?php

namespace WCS\CalendrierBundle\Service\PeriodesAnneeScolaire;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes;


class PeriodesAnneeScolaire
{
    /*==========================================================================================================
        Constantes de classe (pour rappel : elle sont publique)
    ==========================================================================================================*/

    const NB_EVENTS_PAR_AN = 7;

    const EVENT_PRE_RENTREE         = 0;
    const EVENT_RENTREE             = 1;
    const EVENT_TOUSSAINT           = 2;
    const EVENT_NOEL                = 3;
    const EVENT_HIVER               = 4;
    const EVENT_PRINTEMPS           = 5;
    const EVENT_ETE                 = 6;

    const VACANCE_TOUSSAINT         = 0;
    const VACANCE_NOEL              = 1;
    const VACANCE_HIVER             = 2;
    const VACANCE_PRINTEMPS         = 3;

    const CLASSE_RENTREE            = 0;
    const CLASSE_TOUSSAINT_NOEL     = 1;
    const CLASSE_NOEL_HIVER         = 2;
    const CLASSE_HIVER_PRINTEMPS    = 3;
    const CLASSE_PRINTEMPS_ETE      = 4;


    /*==========================================================================================================
        Méthodes
    ==========================================================================================================*/

    /**
     * Retourne l'année scolaire
     * @return \WCS\CalendrierBundle\Service\Periode\Periode|Periode
     */
    public function getAnneeScolaire()
    {
        return $this->annee_scolaire;
    }

    /**
     * Retourne les vacances entre la date de rentrée scolaire
     * et la fin de l'année scolaire. Donc les vacances d'été
     * ne sont pas inclus.
     *
     * @return array tableau indexé des vacances scolaires. Utiliser les constantes VACANCE_* pour
     * accéder au données vacances.
     */
    public function getPeriodesEnVacance()
    {
        return $this->list_vacances;
    }


    /**
     * Renvoit les périodes en classe.
     * @return array tableau indexé des périodes en classe. Utiliser les constantes CLASSE_* pour
     * accéder au données en classe.
     */
    public function getPeriodesEnClasse()
    {
        return $this->list_enclasse;
    }

    /**
     * Renvoit la période de vacance durant laquelle se trouve une date donnée
     *
     * @param $date
     * @return null|\WCS\CalendrierBundle\Service\Periode\Periode
     */
    public function findVacancesFrom($date)
    {
        foreach($this->list_vacances as $periode) {
            if ($periode->isDateIncluded($date)) {
                return $periode;
            }
        }
        return null;
    }


    /**
     * Renvoit la période de classe durant laquelle se trouve une date donnée
     *
     * @param $date
     * @return null|\WCS\CalendrierBundle\Service\Periode\Periode
     */
    public function findEnClasseFrom($date)
    {
        foreach($this->list_enclasse as $periode) {
            if ($periode->isDateIncluded($date)) {
                return $periode;
            }
        }
        return null;
    }


    /**
     * Renvoit la période de classe en cours.
     *
     * @param $date
     * @return null|\WCS\CalendrierBundle\Service\Periode\Periode
     */
    public function getCurrentOrNextPeriodeEnClasse()
    {
        $dateDay = new \DateTimeImmutable($this->date_du_jour);

        foreach($this->list_enclasse as $periode) {
            if ($periode->isDateIncluded($this->date_du_jour)) {
                return $periode;
            }
            // cela signifie qu'à la date du jour la période en classe est passée
            // donc on prend la suivante
            if ($periode->getDebut() > $dateDay) {
                return $periode;
            }
        }
        return null;
    }

    /**
     * @return \WCS\CalendrierBundle\Utils\DatesPeriodIterator
     */
    public function getMonths()
    {
        return $this->annee_scolaire->getMonthIterator();
    }

    /*==========================================================================================================
        Constructeur
        methodes privées
        et attributs
    ==========================================================================================================*/

    /**
     * PeriodesAnneeScolaire constructor.
     *
     * A partir d'un tableau indexé de périodes, le constructeur
     * génère la liste des périodes scolaires (vacances, en classe)
     *
     * @param array $array_periodes tableau indexé de Periode. Doit être un multiple de NB_EVENTS_PAR_AN
     * @param string date du jour au format 'Y-m-d'
     */
    public function __construct($array_periodes, $date_du_jour)
    {
        $this->date_du_jour = $date_du_jour;

        $oneDay     = new \DateInterval('P1D');

        // période "année scolaire"
        $this->annee_scolaire = new Periode(
            $array_periodes[self::EVENT_RENTREE]->getDebut(),
            $array_periodes[self::EVENT_ETE]->getDebut(),
            "Année scolaire"
        );

        // liste des vacances
        $vacances = array();
        foreach($array_periodes as $index => $periode) {
            if ($index!=self::EVENT_PRE_RENTREE &&
                $index!=self::EVENT_RENTREE &&
                $index!=self::EVENT_ETE) {

                // la date de début des vacances dans le calendrier
                // correspond à la date au soir, donc
                // la période débute réellement le lendemain
                $dDebut = $periode->getDebut()->add($oneDay);

                // en revanche, ces vacances se termine le même jour
                // que celui indiqué dans le calendrier
                $dFin   = $periode->getFin();

                $enVacance = new Periode($dDebut, $dFin);
                array_push($vacances, $enVacance);
            }
        }
        $this->list_vacances = new ListPeriodes($vacances);

        // liste des périodes en classe
        $enClasses      = array();
        $dClasseDebut   = $this->annee_scolaire->getDebut();
        foreach($array_periodes as $index => $periode) {
            if ($index!=self::EVENT_PRE_RENTREE &&
                $index!=self::EVENT_RENTREE) {

                // la date de fin de classe correspond à
                // la date de debut de la période "vacances"
                $dClasseFin = $periode->getDebut();

                $enClasse = new Periode($dClasseDebut, $dClasseFin);
                array_push($enClasses, $enClasse);

                // prépare la date de début de la prochaine période,
                // qui correspond à la date de fin de la période "vacance" + 1 journée
                $dClasseDebut = $periode->getFin()->add($oneDay);
            }
        }
        $this->list_enclasse = new ListPeriodes($enClasses);
    }

    /**
     * @var \WCS\CalendrierBundle\Service\Periode\Periode
     */
    private $annee_scolaire;

    /**
     * @var \WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes
     */
    private $list_vacances;

    /**
     * @var arrau de \WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes
     */
    private $list_enclasse;

    /**
     * @var string date au format 'Y-m-d'
     */
    private $date_du_jour;
}
