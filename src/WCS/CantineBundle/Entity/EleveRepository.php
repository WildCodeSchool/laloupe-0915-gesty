<?php
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 09/12/2015
 * Time: 09:57
 */

namespace WCS\CantineBundle\Entity;

use Doctrine\ORM\EntityRepository;

class EleveRepository extends EntityRepository
{
    public function findByDay(\DateTime $date)
    {
        // Format the date
        $day = $date->format('Y-n-j');

        // Request pupils to the database from a certain date
        // lien : http://symfony.com/doc/current/book/doctrine.html (src/AppBundle/Entity/ProductRepository.php)
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Eleve e WHERE e.dates LIKE :day'
            )
            ->setParameter(':day', "%".$day."%")
            ->getResult();
    }
}