<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WCS\CalendrierBundle\Service\Calendrier\ActivityType;

class EmployeeController extends Controller
{
    /**
     * @return \DateTimeImmutable the "date now" defined by the service
     */
    protected function getDateDay()
    {
        return $this->container->get('wcs.datenow')->getDate();
    }

    /**
     * @return \WCS\CantineBundle\Entity\ActivityRepositoryAbstract
     */
    protected function getRepository($entityName)
    {
        return $this->getDoctrine()
            ->getEntityManager()
            ->getRepository($entityName);
    }

    /**
     * @param $activity
     * @return bool
     */
    protected function isDayOff($activity)
    {
        $activityType = [
            'cantine'       => ActivityType::CANTEEN,
            'tap'           => ActivityType::TAP,
            'garderie_matin'=> ActivityType::GARDERIE_MORNING,
            'garderie_soir' => ActivityType::GARDERIE_EVENING
        ];

        return $this->container->get('wcs.calendrierscolaire')->isDayOff(
            $activityType[$activity],
            $this->getDateDay()
        );
    }
}
