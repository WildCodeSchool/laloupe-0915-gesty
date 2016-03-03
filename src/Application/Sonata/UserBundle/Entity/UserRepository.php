<?php

namespace Application\Sonata\UserBundle\Entity;


class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function count()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
