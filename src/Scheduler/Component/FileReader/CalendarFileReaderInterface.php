<?php
namespace Scheduler\Component\FileReader;


interface CalendarFileReaderInterface
{
    /**
     * Instanciate the calendar with the file url to
     * the Calendar file.
     *
     * @param string $url path or url to the calendar file.
     *
     * @throws Exception\NoFilenameException
     */
    function __construct($url);

    /**
     * Read a Calendar file and return events recorded in
     * the file.
     *
     * Throws exception in case of invalid file
     *
     * @return \Scheduler\Component\DateContainer\Period[]
     *
     * @throws Exception\FileNotFoundException
     * @throws Exception\InvalidFileException
     *
     */
    function loadEvents();
}
