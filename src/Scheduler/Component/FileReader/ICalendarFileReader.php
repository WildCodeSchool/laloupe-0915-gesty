<?php
/*
 * ICalendarFileReader
 *
 * Chargé de lire un fichier iCalendar, ayant l'extension ".ics", et de fournir la liste des
 * événements (date de début, date de fin et description).
 *
 * Les dates de début et de fin sont inclus. Autrement dit, un événement qui débute un 1er avril et se termine
 * le 20 inclus renverra comme période (01/04 - 20/04).
 *
 * La lecture du fichier se fait à l'instanciation. En cas d'erreur (fichier introuvable ou invalide)
 * une exception est levée.
 *
 * Les événements ne sont pas triés par ordre de date, ils sont lus tels qu'ils apparaissent dans
 * le fichier iCalendar.
 *
 * Pour accéder aux évenements, utiliser la méthode "getEvents".
 *
 *
 */
namespace Scheduler\Component\FileReader;

use Scheduler\Component\DateContainer\Period;

class ICalendarFileReader implements CalendarFileReaderInterface
{
    /**
     * Construction
     *
     * @param {string} $filename chemin ou URL vers le fichier iCalendar (extension .ics)
     *
     * @throws Exception\NoFilenameException
     */
    public function __construct($url)
    {
        if (!$url) {
            throw new Exception\NoFilenameException('ICalendar file');
        }
        $this->url = $url;
    }


    /**
     * Read the whole file in a Period array, line by line.
     *
     * @return Period[]
     *
     * @throws Exception\FileNotFoundException
     * @throws Exception\InvalidFileException
     */
    public function loadEvents()
    {
        if (!is_file($this->url)) {
            throw new Exception\FileNotFoundException($this->url);
        }

        try  {
            $lines = \file(
                $this->url,
                FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
            );
        }
        catch(\Exception $e) {
            throw new Exception\InvalidFileException('Icalendar file', $this->url);
        }

        if (FALSE === $lines || !\is_array($lines)) {
            throw new Exception\InvalidFileException('Icalendar file', $this->url);
        }

        if (FALSE === \stristr($lines[0], 'BEGIN:VCALENDAR')) {
            throw new Exception\InvalidFileException('Icalendar file', $this->url);
        }


        return $this->extractEvents($lines);   // @codeCoverageIgnore
    }

    /**
     * @param array $lines
     * @return Period[]
     * 
     * @codeCoverageIgnore
     */
    private function extractEvents(array $lines)
    {
        $tmp_periods = array();
        $dateDebut  = null;
        $dateFin    = null;
        $desc       = '';

        foreach ($lines as $line) {
            $line = trim($line);
            $tmp_array  = $this->keyValueFromString($line);
            if ($tmp_array === false) {
                continue;
            }

            list($keyword, $value) = $tmp_array;

            switch ($line) {
                case "BEGIN:VEVENT":
                    $dateDebut = null;
                    $dateFin = null;
                    $desc = '';
                    break;

                case "END:VEVENT":
                    $tmp_periods[] = new Period($dateDebut, $dateFin, $desc);
                    break;

                default:
                    if (stristr($keyword, "DTSTART")) {
                        $dateDebut = new \DateTimeImmutable($value);
                        // in case there is no end date
                        $dateFin = $dateDebut;
                    }
                    if (stristr($keyword, "DTEND")) {
                        $dateFin = new \DateTimeImmutable($value);
                        if ($dateFin > $dateDebut) {
                            // in the iCalendar norm, the end date
                            // is always greater of 1 day than
                            // the real end date of an event
                            $dateFin = $dateFin->sub(new \DateInterval('P1D'));
                        }
                    }
                    if (stristr($keyword, "DESCRIPTION")) {
                        $desc = $value;
                    }
                    break;
            }
        }
        return $tmp_periods;
    }

    /**
     * Get a key-value pair of a string.
     *
     * @param string $text which is like "VCALENDAR:Begin" or "LOCATION:"
     *
     * @return array array("VCALENDAR", "Begin")
     *
     * @codeCoverageIgnore
     */
    private function keyValueFromString($text)
    {
        preg_match("/([^:]+)[:]([\w\W]*)/", $text, $matches);
        if (count($matches) == 0) {
            return false;
        }
        $matches = array_splice($matches, 1, 2);
        return $matches;
    }

    /**
     * @var string
     */
    private $url;
}
