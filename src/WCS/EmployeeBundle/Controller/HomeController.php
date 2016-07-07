<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;


/**
 * List controller.
 *
 */
class HomeController extends ActivityControllerBase
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $this->resetSessionSelectedEleves($request);
        return $this->render('WCSEmployeeBundle::index.html.twig');
    }

    /**
     * @param Request $request
     * @param $activity
     * @return mixed
     */
    public function showSchoolsAction(Request $request, $activity)
    {
        $this->resetSessionSelectedEleves($request);
        $params = $this->getParameters($activity);
        if (!$params) {
            return $this->redirectToRoute('wcs_employee_home');
        }

        $em = $this->getDoctrine()->getManager();
        $schools = $em->getRepository('WCSCantineBundle:School')->findBy($params['filters']);

        return $this->render('WCSEmployeeBundle::schools.html.twig', array(
            'title'     => $params['title'],
            'schools'   => $schools,
            'is_day_off'=> $this->isDayOff($activity),
            'day_off_message' => $params['day_off_message']
        ));
    }


    /**
     * @param $activity
     * @return array|null
     */
    protected function getParameters($activity)
    {
        $params['cantine'] = [
            'filters'           => array('active_cantine' => true),
            'title'             => "Restaurant scolaire : sélectionnez l'école",
            'day_off_message'   => "Pas de restauration scolaire\naujourd'hui"
        ];

        $params['tap'] = [
            'filters'           => array('active_tap' => true),
            'title'             => "TAP : sélectionnez l'école",
            'day_off_message'   => "Pas de TAP\naujourd'hui"
        ];

        $params['garderie_matin'] = [
            'filters'           => array('active_garderie' => true),
            'title'             => "Garderie matin : sélectionnez l'école",
            'day_off_message'   => "Pas de garderie\nce matin"
        ];

        $params['garderie_soir'] = [
            'filters'           => array('active_garderie' => true),
            'title'             => "Garderie soir : sélectionnez l'école",
            'day_off_message'   => "Pas de garderie\nce soir"
        ];

        if (isset($params[$activity])) {
            return $params[$activity];
        }

        return null;
    }
}
