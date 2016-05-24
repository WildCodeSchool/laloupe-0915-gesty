<?php
namespace WCS\CalendrierBundle\Service\Periode\Exception;
use WCS\CalendrierBundle\Service\Periode\Exception\PeriodeException;

class IllSortedDatesException extends PeriodeException
{
    public function __construct($dateDebut, $dateFin)
    {
        parent::__construct(
            'Date de début doit être inférieure à la date de fin : ('.
            'ici date debut = '.$dateDebut.', date fin = '.$dateFin,
            0, null);
    }
}