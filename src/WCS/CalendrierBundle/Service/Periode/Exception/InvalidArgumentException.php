<?php
namespace WCS\CalendrierBundle\Service\Periode\Exception;
use WCS\CalendrierBundle\Service\Periode\Exception\PeriodeException;

class InvalidArgumentException extends PeriodeException
{
    public function __construct($code)
    {
        $message = "possède un type non valide, seuls les \\DateTime, \\DateTimeImmutable et string au format 'Y-m-d' sont autorisés";
        if (self::DATE_DEBUT == $code) {
            parent::__construct('La date de début '.$message, $code, null);
        }

        if (self::DATE_FIN == $code) {
            parent::__construct('La date de fin '.$message, $code, null);
        }
        if (self::DATE == $code) {
            parent::__construct('La date '.$message, $code, null);
        }
    }
}