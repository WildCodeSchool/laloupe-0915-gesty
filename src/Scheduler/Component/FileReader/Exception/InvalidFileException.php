<?php
namespace Scheduler\Component\FileReader\Exception;

class InvalidFileException extends \RuntimeException
{
    public function __construct($fileTypeExpectedMessage, $filepath)
    {
        parent::__construct('Invalid file (expected : '.$fileTypeExpectedMessage.' - given :'.$filepath);
    }
}
