<?php
namespace WCS\CantineBundle\Entity;


abstract class ActivityRepositoryAbstract extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param School $school
     * @param array $options user options to pass to the method
     * @return mixed
     */
    abstract public function getTodayList(School $school, $options);
}