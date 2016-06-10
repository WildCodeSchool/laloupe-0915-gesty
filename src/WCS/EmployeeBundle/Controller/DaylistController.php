<?php
namespace WCS\EmployeeBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\School;
use WCS\EmployeeBundle\Controller\Mapper\ActivityMapperInterface;

class DaylistController extends EmployeeController
{
    /**
     * @param Request $request
     * @param School $school
     * @param $activity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, School $school, $activity)
    {
        // ensure the current day is not off
        if ($this->isDayOff($activity)) {
            return $this->redirectToRoute('wcs_employee_home');
        }

        // get the mapper
        $mapper = $this->getActivityMapper($activity);
        if (is_null($mapper)) {
            return $this->redirectToRoute('wcs_employee_home');
        }

        // the register controller
        $ctrl_register = new RegisterController($mapper);
        $ctrl_register->setContainer($this->container);
        $register_infos = $ctrl_register->showAction($request, $school, $activity);
        if (!empty($register_infos['redirect_to'])) {
            return $this->redirect( $register_infos['redirect_to'] );
        }

        // the validation controller
        $ctrl_validate = new ValidateController($mapper);
        $ctrl_validate->setContainer($this->container);
        $validate_infos = $ctrl_validate->showAction($request, $school, $activity);
        if (!empty($validate_infos['redirect_to'])) {
            return $this->redirect( $validate_infos['redirect_to'] );
        }

        // return the response with parameters
        $data = array_merge($register_infos, $validate_infos);
        $data['title']      = $mapper->getTodayListTitle();
        $data['date_day']   = $this->getDateDay();

        return $this->render('WCSEmployeeBundle::daylist.html.twig', $data);
    }


    /**
     * @param Request $request
     * @param School $school
     * @param $activity
     * @param $id_activity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request, School $school, $activity, $id_activity)
    {
        // ensure the current day is not off
        if ($this->isDayOff($activity)) {
            return $this->redirectToRoute('wcs_employee_home');
        }

        // get the mapper
        $mapper = $this->getActivityMapper($activity);
        if (is_null($mapper)) {
            return $this->redirectToRoute('wcs_employee_home');
        }


        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository($mapper->getEntityClassName())->find($id_activity);
        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute(
            'wcs_employee_daylist',
            array('activity' => $activity, 'id' => $school->getId())
        );
    }



    /**
     * @param string $activity
     * @return null|ActivityMapperInterface
     */
    private function getActivityMapper($activity)
    {
        if (isset($this->listActivityMappers[$activity])) {
            return $this->listActivityMappers[$activity];
        }
        return null;
    }


    /**
     * DaylistController constructor.
     */
    public function __construct()
    {
        $this->listActivityMappers = [
            'cantine'           => new Mapper\LunchMapper(),
            'tap'               => new Mapper\TapMapper(),
            'garderie_matin'    => new Mapper\GarderieMorningMapper(),
            'garderie_soir'     => new Mapper\GarderieEveningMapper()
        ];
    }


    /**
     * @var ActivityMapperInterface[]
     */
    private $listActivityMappers;
}
