<?php
namespace WCS\EmployeeBundle\Controller\Mapper;


class GarderieEveningMapper implements ActivityMapperInterface
{
    /**
     * @return string the title displayed in the today list view
     */
    public function getTodayListTitle()
    {
        return "Garderie soir - Feuille de présence";
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
    public function updateEntity($entity, \DateTimeImmutable $date_day)
    {
        $entity->setDate($date_day);
        $entity->setEnableEvening(true);
    }

    /**
     * @inheritdoc
     */
    public function getDayListAdditionalOptions()
    {
        return array('is_morning'=>false);
    }

}
