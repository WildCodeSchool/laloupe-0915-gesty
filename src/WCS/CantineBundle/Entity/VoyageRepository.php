<?php

namespace WCS\CantineBundle\Entity;

class VoyageRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Retourne les voyages qui débutent au plus tôt "demain"
     *
     * @param $options
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryByEnabledAndDivisions($options)
    {
        $division   = $options['division'];
        $day        = $options['date_day'];

        return $this->createQueryBuilder('v')

            ->join('v.divisions', 'd')
            ->where("v.estAnnule = FALSE
                AND d = :division AND v.date_debut > :day")
            ->orderBy('v.date_debut')
            ->setParameter(':division', $division)
            ->setParameter(':day', $day);
    }
}
