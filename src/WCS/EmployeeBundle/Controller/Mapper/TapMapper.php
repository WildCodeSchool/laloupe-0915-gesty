<?php

namespace WCS\EmployeeBundle\Controller\Mapper;


class TapMapper implements ActivityMapperInterface
{
    /**
     * @return string the title displayed in the today list view
     */
    public function getTodayListTitle()
    {
        return "TAP - Feuille de présence";
    }
    
    /**
     * @return string fully qualified entity class name
     */
    public function getEntityClassName()
    {
        return 'WCS\CantineBundle\Entity\Tap';
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

    /**
     * @inheritdoc
     */
    public function getActivityType()
    {
        return \WCS\CantineBundle\Entity\ActivityType::TAP;
    }

}
