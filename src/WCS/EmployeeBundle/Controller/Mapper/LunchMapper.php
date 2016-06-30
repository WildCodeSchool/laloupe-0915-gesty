<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 07/06/16
 * Time: 12:14
 */

namespace WCS\EmployeeBundle\Controller\Mapper;


use WCS\CantineBundle\Entity\ActivityBase;

class LunchMapper implements ActivityMapperInterface
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
    public function preUpdateEntity(ActivityBase $entity)
    {
    }

    /**
     * @inheritdoc
     */
    public function getDayListAdditionalOptions()
    {
        return array();
    }

    /**
     * @inheritdoc
     */
    public function getActivityType()
    {
        return \WCS\CantineBundle\Entity\ActivityType::CANTEEN;
    }
}
