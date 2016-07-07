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
use WCS\CantineBundle\Entity\School;
use WCS\CantineBundle\Service\GestyScheduler\ActivityType;

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
        /*
        return $this->container->get('wcs.calendrierscolaire')->isDayOff(
            $this->getDateDay(),
            array('activity_type' => $this->activityTypes[$activity])
        );
        */
        return $this->container->get('wcs.gesty.scheduler')->isDayOff(
            $this->getDateDay(),
            $this->activityTypes[$activity]
        );
    }

    /**
     * @return \DateTimeImmutable the "date now" defined by the service
     */
    protected function getDateDay()
    {
        return $this->container->get('wcs.datenow')->getDate();
    }

    /**
     * @param School $school
     * @param string $activity
     * @return boolean true if the activity is enabled for the school
     */
    protected function isActivityEnabled(School $school, $activity)
    {
        if ($activity == 'cantine' && $school->getActiveCantine()) {
            return true;
        }
        if ($activity == 'tap' && $school->getActiveTap()) {
            return true;
        }
        if ($activity == 'garderie_matin' && $school->getActiveGarderie()) {
            return true;
        }
        if ($activity == 'garderie_soir' && $school->getActiveGarderie()) {
            return true;
        }
        return false;
    }
}
