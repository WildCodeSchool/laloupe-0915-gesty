<?php
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 09/12/2015
 * Time: 09:57
 */

namespace WCS\CantineBundle\Entity;

use Doctrine\ORM\EntityRepository;
use WCS\CalendrierBundle\Service\Periode\Periode;

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
             FROM WCSCantineBundle:Eleve el
             LEFT JOIN el.voyages voys
             WHERE el.user=:user
             ORDER BY el.prenom ASC, voys.date_debut ASC"
        )->setParameter("user", $user);

        $results = $query->getResult();

        return $results;
    }

    /**
     * @param $userParent \WCS\Application\UserBundle\Entity\User
     */
    public function findNbEnfantInscritsVoyage($userParent)
    {
        $em = $this->getEntityManager();

        $now = new \DateTime();
        $query = $em->createQuery(
            "SELECT COUNT(el)
             FROM WCSCantineBundle:Eleve el
             JOIN el.voyages voys
             WHERE el.user=:user
                AND voys.date_debut >= :now"
        )
            ->setParameter("user", $userParent)
            ->setParameter("now", $now->format('Y-m-d H:i:s'));

        $results = $query->setMaxResults(1)->getOneOrNullResult();

        return $results[1];
    }
    /**
     * Return all "taps" registered for a given pupil and period.
     *
     * @param Eleve $eleve
     * @param Periode $periode
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllTapsForPeriode(Eleve $eleve, Periode $periode)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT t
                FROM WCSCantineBundle:Tap t
                WHERE t.eleve = :eleve
                    AND t.date >= :dateDebut
                    AND t.date <= :dateFin
                ORDER BY t.date ASC'
        )
            ->setParameter(':eleve', $eleve)
            ->setParameter(':dateDebut', $periode->getDebut())
            ->setParameter(':dateFin', $periode->getFin());
        return new \Doctrine\Common\Collections\ArrayCollection($query->getResult());
    }

    /**
     * Return all "garderies" registered for a given pupil and period.
     *
     * @param Eleve $eleve
     * @param Periode $periode
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllGarderiesForPeriode(Eleve $eleve, Periode $periode)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery(
            'SELECT g
                FROM WCSCantineBundle:Garderie g
                WHERE g.eleve = :eleve
                    AND g.date_heure >= :dateDebut
                    AND g.date_heure <= :dateFin
                ORDER BY g.date_heure ASC'
        )
            ->setParameter(':eleve', $eleve)
            ->setParameter(':dateDebut', $periode->getDebut())
            ->setParameter(':dateFin', $periode->getFin());
        return $query->getResult();
    }
}
