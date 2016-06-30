<?php
/**
 * Created by PhpStorm.
 * User: rod
 * Date: 13/06/16
 * Time: 11:35
 */

namespace WCS\EmployeeBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\ActivityType;

class ActivityControllerBase extends Controller
{
    /**
     * @var array
     */
    protected $activityTypes = [
        'cantine'       => ActivityType::CANTEEN,
        'tap'           => ActivityType::TAP,
        'garderie_matin'=> ActivityType::GARDERIE_MORNING,
        'garderie_soir' => ActivityType::GARDERIE_EVENING
    ];

    /**
     * @param Request $request
     */
    protected function resetSessionSelectedEleves(Request $request)
    {
        foreach($this->activityTypes as $activity => $activityValue) {
            $this->get('session')->set($activity."_list_eleves", $request->get("list_eleves"));
        }
    }

    /**
     * @param $activity
     * @return bool
     */
    protected function isDayOff($activity)
    {
        return $this->container->get('wcs.calendrierscolaire')->isDayOff(
            $this->getDateDay(),
            array('activity_type' => $this->activityTypes[$activity])
        );
    }

    /**
     * @return \DateTimeImmutable the "date now" defined by the service
     */
    protected function getDateDay()
    {
        return $this->container->get('wcs.datenow')->getDate();
    }

}
