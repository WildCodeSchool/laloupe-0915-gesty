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
    public function getTodayList($school)
    {
        // Format the date
        $date = new \DateTime();
        $day = $date->format('Y-m-d');

        // Request pupils to the database from a certain date
        // lien : http://symfony.com/doc/current/book/doctrine.html (src/AppBundle/Entity/ProductRepository.php)
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Eleve e JOIN e.division d WHERE e.dates LIKE :day AND d.school = :place ORDER BY e.nom'
            )
            ->setParameter(':day', "%".$day."%")
            ->setParameter(':place', $school)
            ->getResult();
    }

    public function findByDate($children)
    {
        // Request pupils to the database from a certain date
        // lien : http://symfony.com/doc/current/book/doctrine.html (src/AppBundle/Entity/ProductRepository.php)
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Eleve e WHERE e. LIKE :eleve AND e.'
            )
            ->setParameter(':eleve', "%".$children."%")
            ->getResult();
    }

}