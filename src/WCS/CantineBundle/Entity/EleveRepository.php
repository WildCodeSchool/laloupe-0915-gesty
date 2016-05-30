<?php
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 09/12/2015
 * Time: 09:57
 */

namespace WCS\CantineBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

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

    public function getNumberMonthMeals($eleve_id)
    {
        $dateNow = new \Datetime();
        $dateNowFormat = date_format($dateNow, ('Y-m'));

        $dates = $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Lunch e WHERE e. LIKE :eleve'
            )
            ->setParameter(':eleve', "%".$eleve_id."%")
            ->getResult();

        $count = '';
        foreach ($dates as $date){ 
            if (preg_match('#^'.$dateNowFormat.'#', $date) === 1) {
                $count = count($date);
            }
        }
        return $count;

    }

    public function count()
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Trouve la liste des enfants d'un parent donné (dont l'id est "user_id")
     * Cette liste comporte les enfants, les voyages auxquels ils sont inscrits,
     *
     * @param $user_id      id du parent d'élève
     * @return array        tableau indexé d'entité "Eleve"
     */
    public function findChildren($user)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "SELECT el, voys
             FROM WCSCantineBundle:eleve el
             LEFT JOIN el.voyages voys
             WHERE el.user=:user
             ORDER BY el.prenom ASC, voys.date_debut ASC"
        )->setParameter("user", $user);

        $results = $query->getResult();

        return $results;
    }
    
}