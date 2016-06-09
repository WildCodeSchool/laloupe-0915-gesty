<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 31/05/16
 * Time: 16:52
 */

namespace WCS\CalendrierBundle\Service;


class DateNowException extends \Exception
{
    public function __construct($dateEntered)
    {
        parent::__construct(__CLASS__ ." is called with an invalid date (".$dateEntered."). Check the argument passed in the instanciation or in the service.xml file");
    }
}
