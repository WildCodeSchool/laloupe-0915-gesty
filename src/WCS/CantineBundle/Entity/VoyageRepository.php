<?php

namespace WCS\CantineBundle\Entity;

use \Doctrine\ORM\EntityRepository;

class VoyageRepository extends EntityRepository
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

        $query =  $this->createQueryBuilder('v')

            ->join('v.divisions', 'd')
            ->where("v.estAnnule = FALSE")
            ->andWhere("d = :division")
            ->orderBy('v.date_debut')
            ->setParameter(':division', $division);

        if (isset($options['date_day'])) {
            $day        = $options['date_day'];
            $query->andWhere('v.date_debut > :day')
                ->setParameter(':day', $day);
        }

        return $query;
    }
}
