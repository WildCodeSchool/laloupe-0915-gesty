<?php
namespace WCS\CalendrierBundle\Service;

interface DaysOffInterface
{
    /**
     * @param \WCS\CalendrierBundle\Service\Periode\Periode $periode
     * @param array $options customizable options
     * @return array of dates
     */
    function findDatesWithin(
        \WCS\CalendrierBundle\Service\Periode\Periode $periode,
        array $options
    );

    /**
     * @param \DateTimeInterface $date
     * @param array $options customizable options
     * @return bool
     */
    function isOff(
        \DateTimeInterface $date,
        array $options
    );

}
