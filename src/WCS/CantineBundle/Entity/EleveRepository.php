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
    public function findByDayLesEcureuils(\DateTime $date)
    {
        // Format the date
        $day = $date->format('Y-n-j');

        // Request pupils to the database from a certain date
        // lien : http://symfony.com/doc/current/book/doctrine.html (src/AppBundle/Entity/ProductRepository.php)
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Eleve e WHERE e.dates LIKE :day AND e.Etablissement LIKE :place ORDER BY e.nom'
            )
            ->setParameter(':day', "%".$day."%")
            ->setParameter(':place', "%Les Ecureuils%")
            ->getResult();
    }

    public function findByDayRolandGarros(\DateTime $date)
    {
        // Format the date
        $day = $date->format('Y-n-j');

        // Request pupils to the database from a certain date
        // lien : http://symfony.com/doc/current/book/doctrine.html (src/AppBundle/Entity/ProductRepository.php)
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Eleve e WHERE e.dates LIKE :day AND e.Etablissement LIKE :place ORDER BY e.nom'
            )
            ->setParameter(':day', "%".$day."%")
            ->setParameter(':place', "%Roland-Garros%")
            ->getResult();
    }

    public function findByDayNotreDameDesFleurs(\DateTime $date)
    {
        // Format the date
        $day = $date->format('Y-n-j');

        // Request pupils to the database from a certain date
        // lien : http://symfony.com/doc/current/book/doctrine.html (src/AppBundle/Entity/ProductRepository.php)
        return $this->getEntityManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Eleve e WHERE e.dates LIKE :day AND e.Etablissement LIKE :place ORDER BY e.nom'
            )
            ->setParameter(':day', "%".$day."%")
            ->setParameter(':place', "%Notre Dame%")
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

    public function findEtablissements()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare('SELECT e FROM WCSCantineBundle:Etablissement e WHERE e.id');
        $statement->execute();
        $results = $statement->fetchAll();

        foreach ($results as $result)
        {
            return $result;
        }
    }
}