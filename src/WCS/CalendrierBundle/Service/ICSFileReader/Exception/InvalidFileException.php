<?php
namespace WCS\CalendrierBundle\Service\ICSFileReader\Exception;


class InvalidFileException extends \Exception
{
    public function __construct($filepath, \Exception $previous = null)
    {
        parent::__construct('Fichier iCalendar (.ics) invalide : '.$filepath, 0, $previous);
    }
}