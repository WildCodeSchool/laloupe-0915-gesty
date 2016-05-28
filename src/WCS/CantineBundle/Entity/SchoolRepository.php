<?php

namespace WCS\CantineBundle\Entity;


class SchoolRepository extends \Doctrine\ORM\EntityRepository
{
    public function count()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
