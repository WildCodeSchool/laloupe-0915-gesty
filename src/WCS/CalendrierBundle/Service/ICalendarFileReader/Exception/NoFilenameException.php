<?php
namespace WCS\CalendrierBundle\Service\ICSFileReader\Exception;


class NoFilenameException extends \RuntimeException
{
    public function __construct(\Exception $previous = null)
    {
        parent::__construct('Aucun nom de fichier au format iCalendar (.ics)', 0, $previous);
    }
}
