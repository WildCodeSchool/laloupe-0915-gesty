<?php
namespace WCS\CalendrierBundle\Service;


use Symfony\Component\VarDumper\VarDumper;

class DateNow
{
    /**
     * Instanciate the object either with an empty date, ie, the DateNow will be the
     * current date defined in the system either with an arbitral date
     *
     * DateNow constructor.
     * @param string $dateStr date formatted with 'Y-m-d' or ''
     * @param array $options
     *      available_start : array of key/value with a custom string key and N days as a value
     *                        used by getNextDay() with the same key as argument. N days will be
     *                        added to the current day and will return the correct date.
     * @throws \InvalidArgumentException if the date is not valid
     */
    public function __construct($dateStr='', array $options)
    {
        try {
            $this->dateNow = new \DateTimeImmutable($dateStr);
            $this->options = $options;
        }
        catch(\Exception $e) {
            throw new \InvalidArgumentException(__CLASS__ ." is called with an invalid date (".$dateStr."). Check the argument passed in the instanciation or in the service.xml file");
        }
    }

    /**
     * @return \DateTimeImmutable date courante
     */
    public function getDate()
    {
        return $this->dateNow;
    }


    /**
     *
     * @return \DateTimeImmutable date qui suit la date courante + N jours
     */
    public function getFirstDayAvailable($key)
    {
        $dayStr = $this->dateNow->format('Y-m-d');
        $avail_start = $this->options['available_start'];

        if (isset($avail_start[$key])) {
            $dayStr = $this->dateNow->format('Y-m-d').' +'.$avail_start[$key].' day';
        }

        return new \DateTimeImmutable($dayStr);
    }

    /**
     * @var \DateTimeImmutable
     */
    private $dateNow;

    /**
     * @var array
     */
    private $options;
}
