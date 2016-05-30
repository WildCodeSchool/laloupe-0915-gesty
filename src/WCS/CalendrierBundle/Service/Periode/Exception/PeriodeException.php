<?php
namespace WCS\CalendrierBundle\Service\Periode\Exception;


class PeriodeException extends \Exception
{
    const DATE          = 0;
    const DATE_DEBUT    = 1;
    const DATE_FIN      = 2;
    
    public function __construct($message, $code = DATE, $exception = null)
    {
        parent::__construct($message, $code, $exception);
    }
}
