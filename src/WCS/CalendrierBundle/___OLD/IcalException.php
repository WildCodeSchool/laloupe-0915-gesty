<?php
namespace WCS\CalendrierBundle\DependencyInjection;


class IcalException extends \Exception
{
    const E_UNKNOWN         = 0;
    const E_NOFILENAME      = 1;
    const E_FILENOTFOUND    = 2;
    const E_INVALIDFILE     = 3;

    private $msgs = array(
        self::E_UNKNOWN => 'Exception inconnue',
        self::E_NOFILENAME => 'Aucun nom de fichier calendrier',
        self::E_FILENOTFOUND => 'Fichier calendrier introuvable',
        self::E_INVALIDFILE => 'Fichier calendrier iCalendar invalide'
    );

    public function __construct($message, $code, \Exception $previous = null)
    {
        if (count($this->msgs) <= $code) {
            $code = self::E_UNKNOWN;
        }
        if (!empty($message)) {
            $message = $this->msgs[$code]. " : ".$message;
        }
        else {
            $message = $this->msgs[$code];
        }
        parent::__construct($message, $code, $previous);
    }
}