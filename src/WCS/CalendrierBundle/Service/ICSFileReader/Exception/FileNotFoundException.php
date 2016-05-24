<?php
namespace WCS\CalendrierBundle\Service\ICSFileReader\Exception;


class FileNotFoundException extends \Exception
{
    public function __construct($filepath, \Exception $previous = null)
    {
        parent::__construct('Fichier iCalendar (.ics) introuvable : '.$filepath, 0, $previous);
    }
}
