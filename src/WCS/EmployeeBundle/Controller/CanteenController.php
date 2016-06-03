<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use WCS\EmployeeBundle\Form\Type\LunchType;

use WCS\CantineBundle\Entity\School;
use WCS\CantineBundle\Entity\Lunch;

/**
 * List controller.
 *
 */
class CanteenController extends ActivityBaseController
{
    /**
     * CanteenController constructor.
     */
    public function __construct()
    {
        $this->type_activity = 'cantine';
        $this->form_action = 'wcs_employee_todaylist_cantine';
        $this->setActivityRepositoryName('WCS\CantineBundle\Entity\Lunch');
        $this->setViewTodayList('WCSEmployeeBundle::canteenTodayList.html.twig');
    }

    /**
     * @param School $school
     * @return mixed
     */
    protected function createForms(School $school)
    {
        $data["entity"] = new Lunch();

        $data["forms"]["listType"]  = $this->createForm(
            new LunchType(),
            $data["entity"],
            array('schoolId' => $school->getId())
        );

        return $data;
    }

    /**
     * @param $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function saveActivity(School $school, $entity)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity->setDate(new \DateTime());
        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('wcs_employee_todaylist_cantine', array('id' => $school->getId()));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOrdersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $getNextWeekMealsNumber = $this->getActivityRepository()->getNextWeekMeals();
        $mealsNumber = array_sum($getNextWeekMealsNumber);

        $getNextWeekMealsNumberWithoutPork = $this->getActivityRepository()->getNextWeekMealsWithoutPork();
        $mealsNumberWithoutPork = array_sum($getNextWeekMealsNumberWithoutPork);

        //by default strtotime('last monday') returns the current day on mondays
        $firstDay = date('Y-m-d', strtotime('next monday'));
        $lastDay = date('Y-m-d', strtotime($firstDay.'+ 4 DAY'));
/*
        $lunch = new Lunch();
        $form = $this->createForm(new LunchType(), $lunch);
        $form->handleRequest($request);
*/
        return $this->render('WCSEmployeeBundle::orders.html.twig', array(
            'mealsNumber' => $mealsNumber,
            'mealsNumberWithoutPork' => $mealsNumberWithoutPork,
            'firstDay' => $firstDay,
            'lastDay' => $lastDay,

        ));
    }
}
