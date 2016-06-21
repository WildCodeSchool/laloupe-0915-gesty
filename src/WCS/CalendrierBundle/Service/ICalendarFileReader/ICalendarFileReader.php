<?php
/*
 * ICSFileReader
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
namespace WCS\CalendrierBundle\Service\ICalendarFileReader;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes;



class ICalendarFileReader
{
    /**
     * Construction de l'objet ICSFileReader
     *
     * @param {string} $filename chemin ou URL vers le fichier iCalendar (extension .ics)
     *
     * @throws Exception\NoFilenameException
     * @throws Exception\FileNotFoundException
     * @throws Exception\InvalidFileException
     */
    public function __construct($filename)
    {
        if (!$filename) {
            throw new Exception\NoFilenameException();
        }

        try  {
            $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }
        catch(\Exception $e) {
            throw new Exception\FileNotFoundException($filename);
        }

        if (FALSE === $lines) {
            throw new Exception\FileNotFoundException($filename);
        }

        if (stristr($lines[0], 'BEGIN:VCALENDAR') === false) {
            throw new Exception\InvalidFileException($filename);
        }

        $tmp_periodes = array();
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
                    $tmp_periodes[] = new Periode($dateDebut, $dateFin, $desc);
                    break;

                default:
                    if (stristr($keyword, "DTSTART")) {
                        $dateDebut = new \DateTimeImmutable($value);
                        // au cas où il n'y aurait pas de date de fin
                        $dateFin = $dateDebut;
                    }
                    if (stristr($keyword, "DTEND")) {
                        $dateFin = new \DateTimeImmutable($value);
                        if ($dateFin > $dateDebut) {
                            // la date de fin dans la norme iCalendar est toujours supérieure
                            // d'1 journée à la date de fin réelle d'un évenement.
                            $dateFin = $dateFin->sub(new \DateInterval('P1D'));
                        }
                    }
                    if (stristr($keyword, "DESCRIPTION")) {
                        $desc = $value;
                    }
                    break;
            }
        }

        $this->periodes = new ListPeriodes($tmp_periodes);
    }


    /**
     * @return \WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes
     */
    public function getEvents()
    {
        return $this->periodes;
    }

    /**
     * Get a key-value pair of a string.
     *
     * @param {string} $text which is like "VCALENDAR:Begin" or "LOCATION:"
     *
     * @return {array} array("VCALENDAR", "Begin")
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
     * @var \WCS\CalendrierBundle\Service\ListPeriodes\ListPeriodes
     */
    private $periodes;
}
