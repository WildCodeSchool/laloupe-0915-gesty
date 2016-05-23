<?php
/*==================================================================================================

 Calendrier Scolaire

 ==================================================================================================*/
namespace WCS\CalendrierBundle\Services;

use WCS\CalendrierBundle\DependencyInjection\Ical;
use WCS\CalendrierBundle\Services\CalendrierException;
use WCS\CalendrierBundle\Services\CalendrierPeriode;

class CalendrierScolaire
{
    const URL_FILE = "http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics";

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

    const PERIODE_ENVACANCE         = 1;
    const PERIODE_ENCLASSE          = 2;
    const PERIODE_TOUTES            = self::PERIODE_ENCLASSE | self::PERIODE_ENVACANCE;

    private $periodes = array();

    /**
     * @var \WCS\CalendrierBundle\Services\CalendrierPeriode
     */
    private $annee_scolaire;


    /**
     * CalendrierScolaire constructor.
     * @param $annee
     * @param string $url_ical_file
     * @throws CalendrierException si aucun calendrier n'a été trouvé pour l'année fixée
     */
    public function __construct($annee, $url_ical_file = self::URL_FILE)
    {
        try {
            $ical = new Ical($url_ical_file);

            $this->build($annee, $ical->events());

            unset($ical);
        }
        catch(\Exception $e) {
            throw new CalendrierException('', 0, $e);
        }
    }


    /**
     * @param $annee
     * @param $events
     * @throws CalendrierException si aucun calendrier n'a été trouvé pour l'année fixée
     */
    private function build($annee, $events)
    {
        $index = 0;
        $nbParAn = self::NB_EVENTS_PAR_AN;

        // cherche l'index du 1er événement (pré-rentrée) pour l'année selectionnée

        $found = false;
        foreach($events as $event) {
            $dStart = new \DateTime($event['DTSTART']);

            if ($dStart->format('Y') == $annee && ($index % $nbParAn)==0) {
                $found = true;
                break;
            }
            $index++;
        }

        if (!$found) {
            throw new CalendrierException("Calendrier pour l'année scolaire " .$annee. " introuvable.");
        }
        
        
        // on prépare un intervalle d'une journée pour les rectifications
        // à apporter aux dates des périodes
        $oneDay     = new \DateInterval('P1D');

        // à partir de cet événement (pré-rentrée), stocke toutes les périodes.

        $nbEventsMax = $index + $nbParAn;

        while ( $index < $nbEventsMax ) {

            // on travaille avec l'événement ical pour cette itération
            $event = &$events[$index];

            // récupère la date de début de la période
            // attention, au format iCalendar (voir rfc5545), la date
            // DTSTART est inclusive, ce qui signifie que la date
            // de début de la période correspond bien à cette date DTSTART
            $dStart = null;
            if (key_exists('DTSTART', $event)) {
                $dStart = new \DateTime($event['DTSTART']);
            }

            // récupère la date de fin de la période
            // attention, au format iCalendar (voir rfc5545), la date
            // DTEND n'est pas inclusive, autrement dit,
            // la date de fin de la période doit correspondre au jour
            // qui précède DTEND.
            $dEnd = $dStart;
            if (key_exists('DTEND', $event)) {
                $dEnd = new \DateTime($event['DTEND']);
                $dEnd->sub($oneDay);
            }

            // créé une nouvelle période avec le bon type et la description iCal
            // contenant la description de l'événement (ex : "vacances de la toussain")
            $periode = new CalendrierPeriode(
                $dStart,
                $dEnd,
                $event['DESCRIPTION']
            );

            // ajoute cette période à la classe
            array_push($this->periodes, $periode);

            // passe à l'événement suivant
            $index++;
        }

        // ajoute la période "année scolaire" entière
        $this->annee_scolaire = new CalendrierPeriode(
            $this->periodes[self::EVENT_RENTREE]->getDebut(),
            $this->periodes[self::EVENT_ETE]->getDebut(),
            "Année scolaire"
        );
    }

    /**
     * Retourne une copie car on ne souhaite pas que la liste des périodes
     * créées dans cette classe soient modifiées
     *
     * @return array une copie de la liste des périodes du calendrier scolaire
     */
    public function getPeriodes()
    {
        $copie_periodes = array();
        foreach($this->periodes as $p) {
            $copie_periodes[] = $p;
        }

        return $copie_periodes;
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
        $vacances = array();
        foreach($this->periodes as $index => $periode) {
            if ($index!=self::EVENT_PRE_RENTREE &&
                $index!=self::EVENT_RENTREE &&
                $index!=self::EVENT_ETE) {
                array_push($vacances, $periode);
            }
        }
        return $vacances;
    }


    /**
     * Renvoit les périodes en classe.
     * @return array
     */
    public function getPeriodesEnClasse()
    {
        $oneDay     = new \DateInterval('P1D');
        $enClasses  = array();

        $dClasseDebut     = $this->annee_scolaire->getDebut();

        foreach($this->periodes as $index => $periode) {
            if ($index!=self::EVENT_PRE_RENTREE &&
                $index!=self::EVENT_RENTREE) {

                // la date de fin de classe correspond à
                // la date de debut de la période "vacances"
                $dClasseFin = $periode->getDebut();

                $enClasse = new CalendrierPeriode($dClasseDebut, $dClasseFin);
                array_push($enClasses, $enClasse);

                // prépare la date de début de la prochaine période,
                // qui correspond à la date de fin de la période "vacance" + 1 journée
                $dClasseDebut = $periode->getFin()->add($oneDay);
            }
        }

        return $enClasses;
    }



    /**
     * Retourne la période sélectionnée
     */
    public function getPeriode($indexPeriode)
    {
        return $this->periodes[$indexPeriode];
    }


    /**
     * Retourne la période de vacances sélectionnée
     */
    public function getVacances($indexVacance)
    {
        $vacances = $this->getPeriodesEnVacance();
        return $vacances[$indexVacance];
    }



    /**
     * Retourne la période de classe sélectionnée
     */
    public function getEnClasse($indexEnClasse)
    {
        $enClasses = $this->getPeriodesEnClasse();
        return $enClasses[$indexEnClasse];
    }

    /**
     * Renvoit les périodes en classe.
     * @param \DateTime
     * @return \WCS\CalendrierBundle\Services\CalendrierPeriode
     * renvoit la période dans laquelle se trouve une date donnée
     */
    public function getPeriodeADate(\DateTime $date, $constPeriodes)
    {
        if (self::PERIODE_ENVACANCE & $constPeriodes) {
            $vacances = $this->getPeriodesEnVacance();
            foreach($vacances as $periode) {
                if ($periode->isDateIncluded($date)) {
                    return $periode;
                }
            }
        }

        if (self::PERIODE_ENCLASSE & $constPeriodes) {
            $classes = $this->getPeriodesEnClasse();
            foreach ($classes as $periode) {
                if ($periode->isDateIncluded($date)) {
                    return $periode;
                }
            }
        }

        return null;
    }

    /**
     * @return \WCS\CalendrierBundle\Services\CalendrierPeriode
     * renvoit la période "année scolaire"
     * (date rentrée des classes et date de fin de l'année scolaire)
     */
    public function getAnneeScolaire()
    {
        return $this->annee_scolaire;
    }

/*
    private function getEvent($indexEvent, $key)
    {
        $array = $this->ical->events();
        if (count($array) >= $indexEvent) {
            $d = new \DateTime($array[$indexEvent][$key]);
            return $d;
        }
        return null;
    }

    public function getToussaintStart()
    {
        foreach ($this->array_ical as $key => $value){
            foreach ($value as $test => $essai){
                if (strpos($essai, $this->now['annee'].'10') !== false and $test == 'DTSTART'){
                    return $essai;
                }
            }
        }
    }

    public function getToussaintEnd()
    {
        foreach ($this->array_ical as $key => $value){
            foreach ($value as $test => $essai){
                if (strpos($essai, $this->now['annee'].'11') !== false and $test == 'DTEND'){
                    return $essai;
                }
            }
        }
    }

    public function getNoelStart()
    {
        foreach ($this->array_ical as $key => $value) {
            foreach ($value as $test => $essai) {
                if (strpos($essai, $this->now['annee'].'12') !== false and $test == 'DTSTART') {
                    return $essai;
                }
            }
        }
    }

    public function getNoelEnd()
    {
        if ($this->now['mois'] >= '07'){
            foreach ($this->array_ical as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($this->now['annee'] + 1).'01') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($this->array_ical as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, $this->now['annee'].'01') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        }


    }

    public function getHiverStart()
    {
        if ($this->now['mois'] >= '07') {
            foreach ($this->array_ical as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($this->now['annee'] + 1).'02') !== false and $test == 'DTSTART'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($this->array_ical as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $this->now['annee'] . '02') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getHiverEnd()
    {
        if ($this->now['mois'] >= '07') {
            foreach ($this->array_ical as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($this->now['annee'] + 1).'02') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($this->array_ical as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $this->now['annee'] . '02') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getPrintempsStart()
    {
        if ($this->now['mois'] >= '07') {
            foreach ($this->array_ical as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, ($this->now['annee'] + 1). '04') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($this->array_ical as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $this->now['annee']. '04') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getPrintempsEnd()
    {
        if ($this->now['mois'] >= '07') {
            foreach ($this->array_ical as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, ($this->now['annee'] + 1). '04') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($this->array_ical as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $this->now['annee'] . '04') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        }
    }
*/
}