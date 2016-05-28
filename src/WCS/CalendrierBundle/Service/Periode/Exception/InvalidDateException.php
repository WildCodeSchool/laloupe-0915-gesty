<?php
namespace WCS\CalendrierBundle\Service\Periode\Exception;


class InvalidDateException extends PeriodeException
{
    public function __construct($code, $dateEntered)
    {
        if (self::DATE_DEBUT == $code) {
            parent::__construct("Date de début non valide : ".$dateEntered, $code, null);
        }

        if (self::DATE_FIN == $code) {
            parent::__construct("Date de fin non valide : ".$dateEntered, $code, null);
        }

        if (self::DATE == $code) {
            parent::__construct("Date non valide : ".$dateEntered, $code, null);
        }
    }
}
