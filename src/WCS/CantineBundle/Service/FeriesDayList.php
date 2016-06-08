<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 30/05/16
 * Time: 17:06
 */

namespace WCS\CantineBundle\Service;

use WCS\CalendrierBundle\Service\DaysOffInterface;

class FeriesDayList implements DaysOffInterface
{
    public function __construct(\Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository('WCSCantineBundle:Feries');
    }

    /**
     * @return array of \DateTime
     */
    public function findDatesWithin(\WCS\CalendrierBundle\Service\Periode\Periode $periode)
    {
        $this->datesDayOffArray = $this->repo->findListDateTimes(
            $periode->getFin()->format('Y')
        );
        
        if (is_null($this->datesDayOffArray)) {
            return array();
        }

        return $this->datesDayOffArray;
    }

    private $datesDayOffArray;
    private $repo;
}
