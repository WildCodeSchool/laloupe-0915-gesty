<?php
namespace WCS\CantineBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\VarDumper\VarDumper;

abstract class ActivityRepositoryAbstract extends \Doctrine\ORM\EntityRepository
{
    /**
     * @var OptionsResolver
     */
    private $dayListResolver;

    /**
     * ActivityRepositoryAbstract constructor.
     *
     * @inheritdoc
     */
    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        $this->dayListResolver = new OptionsResolver();

        $this->dayListResolver->setRequired(array(
            'date_day',
            'school'
        ));

        $this->configureDayListOptions($this->dayListResolver);

        parent::__construct($em, $class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    protected function configureDayListOptions(OptionsResolver $resolver)
    {}

    /**
     * @param $options
     */
    public function getActivityDayList($options)
    {
        $options = $this->dayListResolver->resolve($options);
        return $this->getDayList($options);
    }


    /**
     * renvoit la requete suivante :
     * tous les ids des eleves en sortie scolaire (non annulée) pour leur classe
     * à la date donnée
     *
     * @param \DateTimeInterface $date_day
     *
     * @return QueryBuilder
     */
    protected function getQueryEleveIdsEnSortie(
        \DateTimeInterface $date_day
    )
    {
        $query = $this->getEntityManager()->createQueryBuilder()

            ->select('DISTINCT eleve_sortie.id')
            ->from('WCSCantineBundle:Eleve', 'eleve_sortie')
            ->join('eleve_sortie.division', 'classe_sortie')
            ->join('classe_sortie.voyages', 'sortie')
            ->where('sortie.estSortieScolaire = TRUE')
            ->andWhere('sortie.estAnnule = FALSE')
            ->andWhere(':date_day BETWEEN 
                        DATE(sortie.date_debut)
                        AND
                        DATE(sortie.date_fin)')
            ->setParameter(':date_day', $date_day->format('Y-m-d'));

        return $query;
    }

    /**
     * renvoit la requete suivante :
     * tous les ids des eleves INSCRITS en voyage scolaire (non annulée)
     * à la date donnée
     *
     * @param \DateTimeInterface $date_day
     *
     * @return QueryBuilder
     */
    protected function getQueryEleveIdsInscritsVoyage(
        \DateTimeInterface $date_day
    )
    {
        $query = $this->getEntityManager()->createQueryBuilder()

            ->select('DISTINCT eleve_inscrit.id')
            ->from('WCSCantineBundle:Eleve','eleve_inscrit')
            ->join('eleve_inscrit.voyages', 'voyage_scolaire')
            ->where('voyage_scolaire.estAnnule = FALSE')
            ->andWhere(':date_day BETWEEN 
                        DATE(voyage_scolaire.date_debut) 
                        AND 
                        DATE(voyage_scolaire.date_fin)')
            ->setParameter(':date_day', $date_day->format('Y-m-d'));

        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @param QueryBuilder $subQuery
     * @return QueryBuilder $query with query's and subQuery's parameters.
     */
    private function andWherePupilNotIn(
        QueryBuilder $query,
        QueryBuilder $subQuery,
        $EleveQueryAlias
    )
    {
        $query->andWhere(
            $query->expr()->notIn(
                $EleveQueryAlias.".id", $subQuery->getDQL()
            )
        );

        /**
         * @var Parameter $param
         */
        foreach($subQuery->getParameters() as $param) {
            $query->setParameter($param->getName(), $param->getValue(), $param->getType());
        }

        return $query;
    }

    /**
     * inject this method in the query builder
     * to exclude pupils from results
     * who are registered in a travel or
     * in a out school day for a given day.
     *
     * @param QueryBuilder          $query      into which the filtering is added
     * @param string                $EleveQueryAlias the alias of "Eleve" used in the query
     * @param \DateTimeInterface    $date_day   the date to check.
     *
     * @return QueryBuilder  modified with the filter.
     */
    protected function excludePupilsTravellingAt(
        QueryBuilder $query,
        $EleveQueryAlias,
        \DateTimeInterface $date_day
    )
    {
        $this->andWherePupilNotIn(
            $query,
            $this->getQueryEleveIdsEnSortie($date_day),
            $EleveQueryAlias
        );

        $this->andWherePupilNotIn(
            $query,
            $this->getQueryEleveIdsInscritsVoyage($date_day),
            $EleveQueryAlias
        );

        return $query;
    }

    /**
     * @param $options
     */
    abstract protected function getDayList($options);
}
