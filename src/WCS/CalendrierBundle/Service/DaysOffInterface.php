<?php
namespace WCS\CalendrierBundle\Service;

interface DaysOffInterface
{
    /**
     * @return array of dates
     */
    public function findDatesWithin(
        \WCS\CalendrierBundle\Service\Periode\Periode $periode
    );

    /**
     * @param \DateTimeInterface $date
     * @return bool
     */
    public function isOff(
        \DateTimeInterface $date
    );
}
