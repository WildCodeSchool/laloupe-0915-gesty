<?php
namespace WCS\EmployeeBundle\Controller\Mapper;


interface ActivityMapperInterface
{
    /**
     * @return string the title displayed in the today list view
     */
    public function getTodayListTitle();

    /**
     * @return string fully qualified entity class name
     */
    public function getEntityClassName();

    /**
     * @return array index array of options expected by the EntityRepository:getDayList
     */
    public function getDayListAdditionalOptions();

    /**
     * @param $entity
     * @param \DateTimeImmutable $date_day
     */
    public function updateEntity($entity, \DateTimeImmutable $date_day);
}