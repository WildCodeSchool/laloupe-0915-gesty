<?php
namespace WCS\EmployeeBundle\Controller\Mapper;


use WCS\CantineBundle\Entity\ActivityBase;

class GarderieMorningMapper implements ActivityMapperInterface
{
    /**
     * @return string the title displayed in the today list view
     */
    public function getTodayListTitle()
    {
        return "Garderie matin - Feuille de présence";
    }

    /**
     * @return string fully qualified entity class name
     */
    public function getEntityClassName()
    {
        return 'WCS\CantineBundle\Entity\Garderie';
    }

    /**
     * @inheritdoc
     */
    public function preUpdateEntity(ActivityBase $entity)
    {
        /**
         * @var \WCS\CantineBundle\Entity\Garderie $entity
         */
        $entity->setEnableMorning(true);
    }

    /**
     * @inheritdoc
     */
    public function getDayListAdditionalOptions()
    {
        return array('is_morning' => true);
    }

    /**
     * @inheritdoc
     */
    public function getActivityType()
    {
        return \WCS\CantineBundle\Entity\ActivityType::GARDERIE_MORNING;
    }

}
