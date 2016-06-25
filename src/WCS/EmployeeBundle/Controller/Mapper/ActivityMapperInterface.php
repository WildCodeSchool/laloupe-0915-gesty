<?php
namespace WCS\EmployeeBundle\Controller\Mapper;


interface ActivityMapperInterface
{
    /**
     * @return string the title displayed in the today list view
     */
    function getTodayListTitle();

    /**
     * @return string fully qualified entity class name
     */
    function getEntityClassName();
    
    /**
     * @return array index array of options expected by the EntityRepository:getDayList
     */
    function getDayListAdditionalOptions();

    /**
     * @return integer one of the ActivityType:const
     */
    function getActivityType();

    /**
     * @param $entity
     * @param \DateTimeImmutable $date_day
     */
    function updateEntity($entity, \DateTimeImmutable $date_day);
}
