<?php
/**
 * Created by PhpStorm.
 * User: Mikou
 * Date: 09/12/2015
 * Time: 09:57
 */

namespace WCS\CantineBundle\Entity;

use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Scheduler\Component\DateContainer\Period;
use Doctrine\Common\Collections\ArrayCollection;
use WCS\CantineBundle\Service\GestyScheduler\ActivityType;


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

        $this->resolverGetEleves->setDefined(array(
            'school_id',
            'date_day',
            'activity_type' // one of the ActivityType:const
        ));

        $this->resolverGetEleves->setAllowedTypes(
            'date_day', \DateTimeInterface::class
        );

        $this->resolverGetEleves->setDefaults(array(
            'school_id'         => 0
        ));

        parent::__construct($em, $class);
    }


    /**
     * Retourne la liste des eleves non inscrits
     * et qui ne sont ni en voyage scolaire, ni en sortie scolaire
     * pour une date donnée.
     *
     * @param $options
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryUnregisteredPupils($options)
    {
        $options = $this->resolverGetEleves->resolve($options);

        $q = $this->createQueryBuilder('e')
            ->join('e.division', 'd')
            ->join('d.school', 's')
            ->where('s.id = :school_id')
            ;


        // filters pupils who have already joined the given activity
        $subQuery = $this->getEntityManager()->createQueryBuilder()
            ->select('DISTINCT eleve_activity.id')
            ->from('WCSCantineBundle:Eleve', 'eleve_activity')
            ->where(':date_day = DATE(activity.date)');

        switch ($options['activity_type']) {
            case ActivityType::CANTEEN:
                $subQuery->join('eleve_activity.lunches', 'activity');
                break;

            case ActivityType::TAP:
                $subQuery->join('eleve_activity.taps', 'activity');
                break;

            case ActivityType::GARDERIE_MORNING:
                $subQuery->join('eleve_activity.garderies', 'activity');
                $subQuery->andWhere('activity.enable_morning = TRUE');
                break;

            case ActivityType::GARDERIE_EVENING:
                $subQuery->join('eleve_activity.garderies', 'activity');
                $subQuery->andWhere('activity.enable_evening = TRUE');
                break;

            case ActivityType::TRAVEL:
                $subQuery->join('eleve_activity.voyages', 'activity');
                break;
        }

        $q->andWhere(
            $q->expr()->notIn(
                "e.id", $subQuery->getDQL()
            )
        );

        // filter pupils whose class are out of classroom at the given date
        $subQuery = $this->getEntityManager()->createQueryBuilder()

            ->select('DISTINCT eleve_sortie.id')
            ->from('WCSCantineBundle:Eleve', 'eleve_sortie')
            ->join('eleve_sortie.division', 'classe_sortie')
            ->join('classe_sortie.voyages', 'sortie')
            ->where('sortie.estSortieScolaire = TRUE')
            ->andWhere('sortie.estAnnule = FALSE')
            ->andWhere(':date_day BETWEEN 
                        DATE(sortie.date_debut)
                        AND
                        DATE(sortie.date_fin)');
        $q->andWhere(
            $q->expr()->notIn(
                "e.id", $subQuery->getDQL()
            )
        );

        // filter pupils who join a travel at the given date
        $subQuery = $this->getEntityManager()->createQueryBuilder()

            ->select('DISTINCT eleve_inscrit.id')
            ->from('WCSCantineBundle:Eleve','eleve_inscrit')
            ->join('eleve_inscrit.voyages', 'voyage_scolaire')
            ->where('voyage_scolaire.estAnnule = FALSE')
            ->andWhere(':date_day BETWEEN 
                        DATE(voyage_scolaire.date_debut) 
                        AND 
                        DATE(voyage_scolaire.date_fin)');


        $q->andWhere(
            $q->expr()->notIn(
                "e.id", $subQuery->getDQL()
            )
        );

        $q->orderBy('e.nom', 'ASC')
            ->setParameter('school_id', $options['school_id'])
            ->setParameter(':date_day', $options['date_day']->format('Y-m-d'));

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
     * @param User $user     id du parent d'élève
     * @return array        tableau indexé d'entité "Eleve"
     */
    public function findChildren(User $user)
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
     * @param Period $periode
     * @param boolean $subscribed_by_parent_only
     * @return ArrayCollection
     */
    public function findAllTapsForPeriode(Eleve $eleve, Period $periode, $subscribed_by_parent_only)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()
            ->select('t')
            ->from('WCSCantineBundle:Tap', 't')
            ->where('t.eleve = :eleve')
            ->andWhere('t.date >= :dateDebut')
            ->andWhere('t.date <= :dateFin')
            ->orderBy('t.date', 'ASC');

        if ($subscribed_by_parent_only) {
            $query->andWhere('t.subscribed_by_parent = TRUE');
        }

        $query ->setParameter(':eleve', $eleve)
            ->setParameter(':dateDebut', $periode->getFirstDate())
            ->setParameter(':dateFin', $periode->getLastDate());
        return $query->getQuery()->getResult();
    }

    /**
     * Return all "garderies" registered for a given pupil and period.
     *
     * @param Eleve $eleve
     * @param Period $periode
     * @param boolean $subscribed_by_parent_only
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function findAllGarderiesForPeriode(Eleve $eleve, Period $periode, $subscribed_by_parent_only)
    {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder()
            ->select('t')
            ->from('WCSCantineBundle:Garderie', 't')
            ->where('t.eleve = :eleve')
            ->andWhere('t.date >= :dateDebut')
            ->andWhere('t.date <= :dateFin')
            ->orderBy('t.date', 'ASC');

        if ($subscribed_by_parent_only) {
            $query->andWhere('t.subscribed_by_parent = TRUE');
        }

        $query ->setParameter(':eleve', $eleve)
            ->setParameter(':dateDebut', $periode->getFirstDate())
            ->setParameter(':dateFin', $periode->getLastDate());
        return $query->getQuery()->getResult();
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


        return $qb ;
    }

    /**
     * @param Eleve $eleve
     * @param \DateTimeInterface $dateStart
     * @param \DateTimeInterface $dateEnd
     * @return mixed
     */
    public function findTotalCantineFor(
        Eleve $eleve,
        \DateTimeInterface $dateStart,
        \DateTimeInterface $dateEnd
    )

    {


        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(l)')
            ->from('WCSCantineBundle:Lunch', 'l')
            ->where('l.eleve = :eleve')
            ->andWhere('l.date >= :dateStart')
            ->andWhere('l.date <=:dateEnd')

            ->setParameter(':dateStart', $dateStart->format('Y-m-d'))
            ->setParameter(':dateEnd', $dateEnd->format('Y-m-d'))
            ->setParameter(':eleve',$eleve);
        
        $total = $query->getQuery()->getSingleScalarResult();

        
        return $total;
    }

    /**
     * @param Eleve $eleve
     * @param \DateTimeInterface $dateStart
     * @param \DateTimeInterface $dateEnd
     * @return mixed
     */
    public function findTotalTapGarderieFor(
        Eleve $eleve,
        \DateTimeInterface $dateStart,
        \DateTimeInterface $dateEnd
    )
    {

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(g)')
            ->from('WCSCantineBundle:Garderie', 'g')
            ->where('g.eleve = :eleve')
            ->andWhere('g.date >= :dateStart')
            ->andWhere('g.date <=:dateEnd')

            ->setParameter(':dateStart', $dateStart->format('Y-m-d'))
            ->setParameter(':dateEnd', $dateEnd->format('Y-m-d'))
            ->setParameter(':eleve',$eleve);
        
        $totalGarderie = $query->getQuery()->getSingleScalarResult();


        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('COUNT(t)')
            ->from('WCSCantineBundle:Tap', 't')
            ->where('t.eleve = :eleve')
            ->andWhere('t.date >= :dateStart')
            ->andWhere('t.date <=:dateEnd')

            ->setParameter(':dateStart', $dateStart->format('Y-m-d'))
            ->setParameter(':dateEnd', $dateEnd->format('Y-m-d'))
            ->setParameter(':eleve',$eleve);
        
        $totalTaps = $query->getQuery()->getSingleScalarResult();


        if ($totalGarderie < $totalTaps) {
            $total = $totalGarderie;
        }
        else {
            $total = $totalTaps;
        }

        return $total;
    }

    /**
     * override the EntityRepository:findAll
     * @return array
     */
    public function findAll()
    {
        return $this->findBy(array(), array('nom'=>'ASC', 'prenom'=>'ASC'));
    }



}
