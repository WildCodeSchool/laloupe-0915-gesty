<?php
namespace Scheduler\Component\FileReader\Exception;

class NoFilenameException extends \RuntimeException
{
    public function __construct($fileTypeExpectedMessage)
    {
        parent::__construct('No file name given (expected : '.$fileTypeExpectedMessage.')');
    }
}
