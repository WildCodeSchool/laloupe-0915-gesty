<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


/**
 * List controller.
 *
 */
class HomeController extends Controller
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        return $this->render('WCSEmployeeBundle::index.html.twig');
    }

    /**
     * @param Request $request
     * @param $type_activity
     * @return mixed
     */
    public function showSchoolsAction(Request $request, $type_activity)
    {
        $params = $this->getParameters($type_activity);
        if (!$params) {
            return $this->redirectToRoute('wcs_employee_home');
        }

        $em = $this->getDoctrine()->getManager();
        $schools = $em->getRepository('WCSCantineBundle:School')->findBy($params['filters']);

        return $this->render('WCSEmployeeBundle::schools.html.twig', array(
            'schools'          => $schools,
            'title'            => $params['title'],
            'route_todaylist'  => $params['route_todaylist'],
            'type_activity'    => $type_activity
        ));
    }


    /**
     * @param $type_activity
     * @return array|null
     */
    protected function getParameters($type_activity)
    {
        $params['cantine'] = [
            'filters'                   => array('active_cantine' => true),
            'route_todaylist'           => 'wcs_employee_todaylist_cantine',
            'title'                     => "Restaurant scolaire : sélectionnez l'école"
        ];

        if ($this->container->getParameter('wcs_group_tap_garderie') == '1') {
            $params['tap_garderie'] = [
                'filters'           => array('active_tap' => true, 'active_garderie' => true),
                'route_todaylist'   => 'wcs_employee_todaylist_tap_garderie',
                'title'             => "TAP/Garderie : sélectionnez l'école"
            ];
        } else {
            $params['tap'] = [
                'filters'           => array('active_tap' => true),
                'route_todaylist'   => 'wcs_employee_todaylist_tap',
                'title'             => "TAP : sélectionnez l'école"
            ];

            $params['garderie_matin'] = [
                'filters'           => array('active_garderie' => true),
                'route_todaylist'   => 'wcs_employee_todaylist_garderie_matin',
                'title'             => "Garderie matin : sélectionnez l'école"
            ];

            $params['garderie_soir'] = [
                'filters'           => array('active_garderie' => true),
                'route_todaylist'   => 'wcs_employee_todaylist_garderie_soir',
                'title'             => "Garderie soir : sélectionnez l'école"
            ];
        }

        if (isset($params[$type_activity])) {
            return $params[$type_activity];
        }

        return null;
    }
}
