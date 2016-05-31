<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 30/05/16
 * Time: 17:06
 */

namespace WCS\CantineBundle\Service;

use WCS\CalendrierBundle\Service\Periode\Periode;

class FeriesDayList
{
    public function __construct(\Doctrine\ORM\EntityManager $em)
    {
        $this->repo = $em->getRepository('WCSCantineBundle:Feries');
    }

    /**
     * @return array
     */
    public function findDayOffDatesWithin(Periode $periode)
    {
        $this->datesDayOffArray = $this->repo->findListDateTimes(
            $periode->getFin()->format('Y')
        );

        return $this->datesDayOffArray;
    }

    private $datesDayOffArray;
    private $repo;
}