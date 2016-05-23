<?php

namespace WCS\CalendrierBundle\Service\PeriodesAnneeScolaire;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes;


class PeriodesAnneeScolaire
{
    const NB_EVENTS_PAR_AN = 7;

    const EVENT_PRE_RENTREE         = 0;
    const EVENT_RENTREE             = 1;
    const EVENT_TOUSSAINT           = 2;
    const EVENT_NOEL                = 3;
    const EVENT_HIVER               = 4;
    const EVENT_PRINTEMPS           = 5;
    const EVENT_ETE                 = 6;

    public function __construct($array_periodes, $date_today)
    {
        $this->date_today = $date_today;

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
     * Retourne l'année scolaire
     * @return WCS\CalendrierBundle\CalendrierScolaire\Periode\Periode|Periode
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
        return $this->vacances;
    }


    /**
     * Renvoit les périodes en classe.
     * @return array
     */
    public function getPeriodesEnClasse()
    {
        return $this->list_enclasse;
    }

    /**
     * Renvoit la période de vacance durant laquelle se trouve une date donnée
     *
     * @param $date
     * @return null|\WCS\CalendrierBundle\Service\ListPeriodes\WCS\CalendrierBundle\Service\Periode\Periode
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
     * @return null|\WCS\CalendrierBundle\Service\ListPeriodes\WCS\CalendrierBundle\Service\Periode\Periode
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
     * @return mixed
     */
    public function getDateToday()
    {
        return $this->date_today;
    }

    /**
     * @var WCS\CalendrierBundle\CalendrierScolaire\Periode\Periode
     */
    private $annee_scolaire;

    /**
     * @var WCS\CalendrierBundle\CalendrierScolaire\ListPeriodes\ListPeriodes
     */
    private $list_vacances;

    /**
     * @var WCS\CalendrierBundle\CalendrierScolaire\ListPeriodes\ListPeriodes
     */
    private $list_enclasse;

    private $date_today;
}