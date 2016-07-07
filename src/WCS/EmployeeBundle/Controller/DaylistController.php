<?php
namespace WCS\EmployeeBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WCS\CantineBundle\Entity\School;
use WCS\EmployeeBundle\Controller\Mapper\ActivityMapperInterface;
use WCS\EmployeeBundle\Controller\ViewBuilder\ListViewBuilder;
use WCS\EmployeeBundle\Controller\ViewBuilder\ValidateViewBuilder;


class DaylistController extends ActivityControllerBase
{
    /**
     * @param Request $request
     * @param School $school the current selected school
     * @param string $activity one of the activities set in the route
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request, School $school, $activity)
    {
        // ensure the school has the activity

        if (!$this->isActivityEnabled($school, $activity)) {
            throw new NotFoundHttpException();
        }

        // ensure the current day is not off

        if ($this->isDayOff($activity)) {
            return $this->redirectToRoute('wcs_employee_home');
        }


        // get the mapper for the requested activity

        $mapper = $this->getActivityMapper($activity);
        if (is_null($mapper)) {
            return $this->redirectToRoute('wcs_employee_home');
        }


        // set up the list of pupils view builder

        $pupilsListBuilder = new ListViewBuilder($mapper);
        $pupilsListBuilder->setContainer($this->container);

        // build the validation view

        $list_infos = $pupilsListBuilder->buildView($request, $school, $activity);

        if (!empty($list_infos['redirect_to'])) {
            return $this->redirect( $list_infos['redirect_to'] );
        }


        // set up the validation view builder

        $validationBuilder = new ValidateViewBuilder($mapper);
        $validationBuilder->setContainer($this->container);

        // build the validation view
        $validate_infos = $validationBuilder->buildView($request, $school, $activity);

        if (!empty($validate_infos['redirect_to'])) {
            return $this->redirect( $validate_infos['redirect_to'] );
        }

        // prepare all template parameters
        $data = array_merge($list_infos, $validate_infos);

        $data['title']      = $mapper->getTodayListTitle();
        $data['date_day']   = $this->getDateDay();

        // return the response with parameters
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

        $key = $request->get('activity').'_list_eleves';
        $this->get('session')->set($key, $request->get("list_eleves"));

        $em = $this->getDoctrine()->getManager();
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
