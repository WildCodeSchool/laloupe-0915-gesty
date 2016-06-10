<?php

namespace WCS\CantineBundle\Entity;

use Doctrine\ORM\EntityRepository;

class DivisionRepository extends EntityRepository
{
    public function getQueryVoyagesAutorises()
    {
        return $this->createQueryBuilder('d')
            ->join('d.school', 's')
            ->where("s.active_voyage = TRUE");
    }
}
