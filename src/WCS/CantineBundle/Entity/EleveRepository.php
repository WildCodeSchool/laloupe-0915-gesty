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

    public function getCurrentWeekMeals()
    {
        $day = date('Y-m-d', strtotime('last monday', strtotime('tomorrow'))); //by default strtotime('last monday') returns the current day on mondays
        $result = [];
        for ($i=1;$i<=4;$i++)
        {
            /*$res = $this->getEntityManager()
                ->createQuery(
                    'SELECT COUNT(d) FROM WCSCantineBundle:Lunch d WHERE d.date LIKE :day'
                )
                ->setParameter(':day', "%".$day."%")
                ->getResult();
            array_push($result, $res[0][1]);
            if ($i===2) $day = date('Y-m-d', strtotime($day.' + 2 DAY')); // Jump Wednesday off
            else $day = date('Y-m-d', strtotime($day.' + 1 DAY'));*/
        }

        return $result;
    }
    public function mealsByMonth($children)
    {
        //Request meals by months by pupils
        return $this->getEntityManager()
            ->createQuery(

                  )
            ->setParameter(':eleve',"%".$children."%")
            ->getResult();



    }
}