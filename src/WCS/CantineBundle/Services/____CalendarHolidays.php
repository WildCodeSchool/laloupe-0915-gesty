<?php

/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 19/01/2016
 * Time: 11:34
 */

namespace WCS\CantineBundle\Services;

use WCS\CantineBundle\DependencyInjection\Ical;

class CalendarHolidays
{
    private $ical;
    public function __construct()
    {
        $this->ical = new Ical(__DIR__."/../../../../app/Resources/files/Calendrier_Scolaire_Zone_B.ics");
//        $this->ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
    }
    
    public function getIcal()
    {
        //$ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        return $this->ical->events();
    }

    // Get the date of the year end
    public function getYearEnd()
    {
        //$ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $this->ical->events();
        return $date = $array[6]['DTSTART'];
    }


    public function getToussaintStart()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));

       // $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");

        $array = $this->ical->events();

        foreach ($array as $key => $value){
            foreach ($value as $test => $essai){
                if (strpos($essai, $anneeActuelle.'10') !== false and $test == 'DTSTART'){
                    return $essai;
                }
            }
        }
    }

    public function getToussaintEnd()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));

        $array = $this->ical->events();

        foreach ($array as $key => $value){
            foreach ($value as $test => $essai){
                if (strpos($essai, $anneeActuelle.'11') !== false and $test == 'DTEND'){
                    return $essai;
                }
            }
        }
    }

    public function getNoelStart()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));

        $array = $this->ical->events();

        foreach ($array as $key => $value) {
            foreach ($value as $test => $essai) {
                if (strpos($essai, $anneeActuelle.'12') !== false and $test == 'DTSTART') {
                    return $essai;
                }
            }
        }
    }

    public function getNoelEnd()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $array = $this->ical->events();

        if ($moisActuel >= '07'){
            foreach ($array as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($anneeActuelle + 1).'01') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, $anneeActuelle.'01') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        }


    }

    public function getHiverStart()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $array = $this->ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($anneeActuelle + 1).'02') !== false and $test == 'DTSTART'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle . '02') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getHiverEnd()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $array = $this->ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($anneeActuelle + 1).'02') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle . '02') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getPrintempsStart()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $array = $this->ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, ($anneeActuelle + 1). '04') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle. '04') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getPrintempsEnd()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $array = $this->ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, ($anneeActuelle + 1). '04') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle . '04') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        }
    }
}