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
    const URL_FILE = "http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics";

    private $array_ical;
    private $now = array('mois'=>'', 'annee'=>'');

    public function __construct($url_ical_file = self::URL_FILE)
    {
        $ical = new Ical($url_ical_file);
        $this->array_ical = $ical->events();

        $now = new \DateTime();
        $this->now['mois']  = $now->format('m');
        $this->now['annee'] = $now->format('Y');
    }

    public function getYearStart()
    {
        return $date = $this->array_ical[0]['DTSTART'];
    }

    public function getYearEnd()
    {
        return $date = $this->array_ical[6]['DTSTART'];
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
}