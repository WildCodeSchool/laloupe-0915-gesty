<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 30/05/16
 * Time: 17:06
 */

namespace WCS\CantineBundle\Service;

use WCS\CalendrierBundle\Service\DaysOffInterface;

class AdditionalDayOffList implements DaysOffInterface
{
    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->repoFeries = $em->getRepository('WCSCantineBundle:Feries');
    }

    /**
     * @return array of \DateTime
     */
    public function findDatesWithin(\WCS\CalendrierBundle\Service\Periode\Periode $periode)
    {
        $daysOffArray = $this->repoFeries->findListDatesWithin(
            $periode->getDebut(),
            $periode->getFin()
        );

        return $daysOffArray;
    }

    /**
     * @inheritdoc
     */
    public function isOff(\DateTimeInterface $date)
    {
        $daysOffArray = $this->repoFeries->findListDatesWithin(
            $date,
            $date
        );
        return (in_array($date, $daysOffArray));
    }

    private $repoFeries;
}
