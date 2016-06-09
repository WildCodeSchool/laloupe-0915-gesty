<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 07/06/16
 * Time: 12:14
 */

namespace WCS\EmployeeBundle\Controller\Mapper;


class CanteenMapper implements ActivityMapperInterface
{
    /**
     * @inheritdoc
     */
    public function getTodayListTitle()
    {
        return "Restaurant scolaire - Feuille de présence";
    }
    
    /**
     * @inheritdoc
     */
    public function getEntityClassName()
    {
        return 'WCS\CantineBundle\Entity\Lunch';
    }

    /**
     * @inheritdoc
     */
    public function updateEntity($entity, \DateTimeImmutable $date_day)
    {
        $entity->setDate($date_day);
    }

    /**
     * @inheritdoc
     */
    public function getDayListAdditionalOptions()
    {
        return array();
    }
}
