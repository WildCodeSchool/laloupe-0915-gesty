<?php

namespace WCS\CantineBundle\Entity;
use Symfony\Component\Validator\Constraints\DateTime;


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
