<?php
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 09/12/2015
 * Time: 09:57
 */

namespace WCS\CantineBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;
use WCS\CalendrierBundle\Service\Periode\Periode;

class EleveRepository extends EntityRepository
{
    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $resolverGetEleves;

    public function __construct(
        \Doctrine\ORM\EntityManager $em,
        \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        $this->resolverGetEleves = new OptionsResolver();
        $this->resolverGetEleves->setDefaults(
            array(
                'enable_canteen' => true,
                'enable_tap' => false,
                'enable_garderie' => false,
                'enable_voyages' => false,
                'school_id' => 0
            )
        );

        parent::__construct($em, $class);
    }

    /**
     * @param $options
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryGetEleves($options)
    {
        $options = $this->resolverGetEleves->resolve($options);

        $q = $this->createQueryBuilder('e')
            ->join('e.division', 'd')
            ->join('d.school', 's')
            ->where('s.id = :school_id')
            ->setParameter('school_id', $options['school_id']);

        if ($options['enable_canteen']) {
            $q->andWhere('s.active_cantine = TRUE');
        }

        if ($options['enable_tap']) {
            $q->andWhere('s.active_tap = TRUE');
        }

        if ($options['enable_garderie']) {
            $q->andWhere('s.active_garderie = TRUE');
        }

        if ($options['enable_voyages']) {
            $q->andWhere('s.active_voyage = TRUE');
        }

        $q->orderBy('e.nom', 'ASC');

        return $q;
    }


    /**
     * @param $id
     * @param $parent_user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByIdAndParent($id, $parent_user)
    {
        return $this->createQueryBuilder('e')
            ->where('e.id = :id')
            ->andWhere('e.user = :parent_user')
            ->setParameter(':id', $id)
            ->setParameter(':parent_user', $parent_user)
            ->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $children
     * @return array
     */
    public function findByDate($children)
    {
        // Request pupils to the database from a certain date
        // lien : http://symfony.com/doc/current/book/doctrine.html (src/AppBundle/Entity/ProductRepository.php)
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Eleve e WHERE e. LIKE :eleve AND e.'
            )
            ->setParameter(':eleve', "%" . $children . "%")
            ->getResult();
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
     * @param $userParent \Application\Sonata\UserBundle\Entity\User
     */
    public function findNbEnfantInscritsVoyage($userParent, \DateTimeInterface $date_day)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            "SELECT COUNT(el)
             FROM WCSCantineBundle:Eleve el
             JOIN el.voyages voys
             WHERE el.user=:user
                AND voys.date_debut >= :date_day"
        )
            ->setParameter("user", $userParent)
            ->setParameter("date_day", $date_day->format('Y-m-d H:i:s'));

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
                    AND g.date >= :dateDebut
                    AND g.date <= :dateFin
                ORDER BY g.date ASC'
        )
            ->setParameter(':eleve', $eleve)
            ->setParameter(':dateDebut', $periode->getDebut())
            ->setParameter(':dateFin', $periode->getFin());
        return $query->getResult();
    }


    public function getQueryElevesAutorisesEnTAP()
    {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('e')
            ->from('WCSCantineBundle:eleve', 'e')
            ->join('e.division', 'd')
            ->join('d.school', 's')
            ->where("s.active_tap = TRUE")
            ->orderBy('e.nom');


        return $qb;
    }

    public function getQueryElevesAutorisesEnGarderie()
    {

        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb->select('e')
            ->from('WCSCantineBundle:eleve', 'e')
            ->join('e.division', 'd')
            ->join('d.school', 's')
            ->where("s.active_garderie = TRUE")
            ->orderBy('e.nom');


        return $qb;
    }

    public function findTotalCantineFor(
        Eleve $eleve,
        \DateTimeInterface $dateStart,
        \DateTimeInterface $dateEnd
    )
    {
        $em = $this->getEntityManager();

        // récupère les garderies, tap
        $query = $em->createQuery(
            ' SELECT COUNT(g)
              FROM WCSCantineBundle:Lunch g
              WHERE (g.date >= :dateStart AND g.date <= :dateEnd)
              AND g.eleve = :eleve'
        )
        ->setParameter(':dateStart', $dateStart->format('Y-m-d'))
        ->setParameter(':dateEnd', $dateEnd->format('Y-m-d'))
        ->setParameter(':eleve', $eleve);
        $total = $query->getOneOrNullResult();

        return $total?$total[1]:0;
    }

    public function findTotalTapGarderieFor(
        Eleve $eleve,
        \DateTimeInterface $dateStart,
        \DateTimeInterface $dateEnd
    )
    {
        /*
        if ($eleve['total_taps'] < $eleve['total_garderies']) {
            $tmp['total_tapgarderie']= $eleve['total_garderies'];
        }
        else {
            $tmp['total_tapgarderie']= $eleve['total_taps'];
        }
        */
        return 0;
    }
}