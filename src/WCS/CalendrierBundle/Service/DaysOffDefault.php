<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 31/05/16
 * Time: 22:22
 */

namespace WCS\CalendrierBundle\Service;
use WCS\CalendrierBundle\Service\Periode\Periode;

class DaysOffDefault implements DaysOffInterface
{
    /**
     * @return array of \DateTime
     */
    public function findDatesWithin(Periode $periode,
                                    array $options)
    {
        return array();
    }

    /**
     * @inheritdoc
     */
    public function isOff(\DateTimeInterface $date,
                          array $options)
    {
        return false;
    }
}
