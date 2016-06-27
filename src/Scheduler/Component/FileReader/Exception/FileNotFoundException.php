<?php
namespace Scheduler\Component\FileReader\Exception;


class FileNotFoundException extends \RuntimeException
{
    public function __construct($filepath)
    {
        parent::__construct('File not found : '.$filepath);
    }
}
