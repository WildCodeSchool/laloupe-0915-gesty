<?php
namespace WCS\EmployeeBundle\Controller\Mapper;


use WCS\CantineBundle\Entity\ActivityBase;

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
     * @param ActivityBase $entity
     * @param \DateTimeImmutable $date_day
     */
    function preUpdateEntity(ActivityBase $entity);
}
