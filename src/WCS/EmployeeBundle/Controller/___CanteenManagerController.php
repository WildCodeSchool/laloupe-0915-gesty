<?php
// src/WCS/CantineBundle/Controller/CanteenManagerController.php

namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use WCS\CantineBundle\Form\Type\GarderiePresentType;
use WCS\CantineBundle\Form\Type\StatusType;
use WCS\CantineBundle\Form\Type\LunchType;
use WCS\CantineBundle\Form\Type\TapPresentType;
use WCS\CantineBundle\Entity\Tap;
use WCS\CantineBundle\Entity\Garderie;
use WCS\CantineBundle\Entity\Lunch;

/**
 * List controller.
 *
 */
class CanteenManagerController extends Controller
{

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        return $this->render('WCSCantineBundle:CanteenManager:index.html.twig');
    }

    public function chooseSchoolAction(Request $request, $type_activity)
    {
        $activity_selected = $this->getParameters($type_activity);
        if (!$activity_selected) {
            return $this->redirectToRoute('wcs_gesty_activities');
        }

        $em = $this->getDoctrine()->getManager();
        $schools = $em->getRepository('WCSCantineBundle:School')->findBy($activity_selected['filters']);

        return $this->render('WCSCantineBundle:CanteenManager:schools.html.twig', array(
            'schools' => $schools,
            'route_todaylist' => $activity_selected['route_todaylist']
        ));
    }

    /**
     * @param $type_activity
     * @return array|null
     */
    private function getParameters($type_activity)
    {
        $activities_params = array();

        $activities_params['cantine'] = [
            'filters'                       => array('active_cantine'=> true),
            'route_todaylist'               => 'wcs_cantine_todayList',
            'class_entity'                  => 'Lunch',
            'class_form_type_activity'      => 'LunchType'
        ];

        if ($this->container->getParameter('wcs_group_tap_garderie') == '1') {
            $activities_params['tap_garderie'] = [
                'filters'           => array('active_tap' => true, 'active_garderie' => true),
                'route_todaylist'   => 'wcs_tap_garderie_todayList'
            ];
        }
        else {
            $activities_params['tap'] = [
                'filters'           => array('active_tap' => true),
                'route_todaylist'   => 'wcs_tap_todayList'
            ];

            $activities_params['garderie'] = [
                'filters'           => array('active_garderie' => true),
                'route_todaylist'   => 'wcs_garderie_todayList'
            ];
        }

        if (isset($activities_params[$type_activity])) {
            return $activities_params[$type_activity];
        }

        return null;
    }

    private function getFromRepositories($school_id, $type_activity)
    {
        $em = $this->getDoctrine()->getManager();
        
        
        $school     = $em->getRepository('WCSCantineBundle:School')->find($school_id);
        $lunches    = $em->getRepository('WCSCantineBundle:Lunch')->getTodayList($school);

    }

    /**
     * Lists all Eleve entities.
     *
     * @param Request $request
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function cantineTodayListAction(Request $request, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('WCSCantineBundle:School')->find($schoolId);
        $lunches = $em->getRepository('WCSCantineBundle:Lunch')->getTodayList($school);

        $lunch = new Lunch();
        $forms["lunchType"]  = $this->createForm(new LunchType(), $lunch, array('schoolId' => $schoolId));
        $forms["statusType"] = $this->createForm(new StatusType(), $lunch);

        $resp = $this->processCantineTodayListForm($request, $forms, $lunch, $schoolId);
        if (!is_null($resp)) {
            return $resp;
        }

        return $this->render('WCSCantineBundle:CanteenManager:cantineTodayList.html.twig', array(
            'lunches' => $lunches,
            'form' => $forms["lunchType"]->createView(),
            'statusForm' => $forms["statusType"]->createView(),
            'school' => $school
        ));
    }


    /**
     * Lists all Eleve entities.
     *
     * @param Request $request
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function tapTodayListAction(Request $request, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('WCSCantineBundle:School')->find($schoolId);
        $taps = $em->getRepository('WCSCantineBundle:Tap')->getTodayList($school);

        $tap = new Tap();
        $forms["tapType"]  = $this->createForm(new TapPresentType(), $tap, array('schoolId' => $schoolId));
        $forms["statusType"] = $this->createForm(new StatusType(), $tap);

        $resp = $this->processTapTodayListForm($request, $forms, $tap, $schoolId);
        if (!is_null($resp)) {
            return $resp;
        }

        return $this->render('WCSCantineBundle:CanteenManager:tapTodayList.html.twig', array(
            'taps' => $taps,
            'form' => $forms["tapType"]->createView(),
            'statusForm' => $forms["statusType"]->createView(),
            'school' => $school
        ));

    }



    /**
     * Lists all Eleve entities.
     *
     * @param Request $request
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function garderieTodayListAction(Request $request, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('WCSCantineBundle:School')->find($schoolId);
        $garderies_matin = $em->getRepository('WCSCantineBundle:Garderie')->getTodayList($school, true);
        $garderies_soir = $em->getRepository('WCSCantineBundle:Garderie')->getTodayList($school, false);

        $garderie = new Garderie();
        $forms["garderieType"]  = $this->createForm(new GarderiePresentType(), $garderie, array('schoolId' => $schoolId));
        $forms["statusType"] = $this->createForm(new StatusType(), $garderie);
/*
        $resp = $this->processTodayListForm($request, $forms, $garderie, $schoolId);
        if (!is_null($resp)) {
            return $resp;
        }
*/
        return $this->render('WCSCantineBundle:CanteenManager:garderieTodayList.html.twig', array(
            'garderies_matin' => $garderies_matin,
            'garderies_soir' => $garderies_soir,
            'form' => $forms["garderieType"]->createView(),
            'statusForm' => $forms["statusType"]->createView(),
            'school' => $school
        ));

    }



    /**
     * Lists all Eleve entities.
     *
     * @param Request $request
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function tapGarderieTodayListAction(Request $request, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $school = $em->getRepository('WCSCantineBundle:School')->find($schoolId);
        $taps = $em->getRepository('WCSCantineBundle:Tap')->getTodayList($school);
        $garderies_matin = $em->getRepository('WCSCantineBundle:Garderie')->getTodayList($school, true);
        $garderies_soir = $em->getRepository('WCSCantineBundle:Garderie')->getTodayList($school, false);

        $tap = new Tap();
        $forms["tapType"]  = $this->createForm(new TapPresentType(), $tap, array('schoolId' => $schoolId));
        $forms["statusType"] = $this->createForm(new StatusType(), $tap);

        $resp = $this->processTapGarderieTodayListForm($request, $forms, $tap, $schoolId);
        if (!is_null($resp)) {
            return $resp;
        }

        return $this->render('WCSCantineBundle:CanteenManager:cantineTodayList.html.twig', array(
            'taps' => $taps,
            'form' => $forms["tapType"]->createView(),
            'statusForm' => $forms["statusType"]->createView(),
            'school' => $school
        ));
    }

    /**
     * @param Request $request
     * @param $forms
     * @param $lunch
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function processCantineTodayListForm(Request $request, $forms, $lunch, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $forms["lunchType"]->handleRequest($request);

        if ($forms["lunchType"]->isValid()) {
            $lunch->setDate(new \DateTime());
            $em->persist($lunch);
            $em->flush();

            return $this->redirectToRoute('wcs_cantine_todayList', array('schoolId' => $schoolId));
        }

        if ($request->getMethod() == 'POST') {
            $forms["statusType"]->handleRequest($request);
            $datas = $forms["statusType"]["status"]->getData();
            foreach (explode(',', $datas) as $id)
            {
                if ($id != '') {
                    $em = $this->getDoctrine()->getManager();
                    $lunches = $em->getRepository('WCSCantineBundle:Lunch')->find($id);
                    $lunches->setStatus('2');
                }
            }
            $em->flush();
            return $this->redirectToRoute('wcs_gesty_schools', array('schoolId' => $schoolId));
        }
        return null;
    }

    /**
     * @param Request $request
     * @param $forms
     * @param $lunch
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function processTapTodayListForm(Request $request, $forms, $tap, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $forms["tapType"]->handleRequest($request);

        if ($forms["lunchType"]->isValid()) {
            $lunch->setDate(new \DateTime());
            $em->persist($lunch);
            $em->flush();

            return $this->redirectToRoute('wcs_cantine_todayList', array('schoolId' => $schoolId));
        }

        if ($request->getMethod() == 'POST') {
            $forms["statusType"]->handleRequest($request);
            $datas = $forms["statusType"]["status"]->getData();
            foreach (explode(',', $datas) as $id)
            {
                if ($id != '') {
                    $em = $this->getDoctrine()->getManager();
                    $lunches = $em->getRepository('WCSCantineBundle:Lunch')->find($id);
                    $lunches->setStatus('2');
                }
            }
            $em->flush();
            return $this->redirectToRoute('wcs_gesty_schools', array('schoolId' => $schoolId));
        }
        return null;
    }

    /**
     * @param Request $request
     * @param $forms
     * @param $lunch
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function processGarderieTodayListForm(Request $request, $forms, $lunch, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $forms["lunchType"]->handleRequest($request);

        if ($forms["lunchType"]->isValid()) {
            $lunch->setDate(new \DateTime());
            $em->persist($lunch);
            $em->flush();

            return $this->redirectToRoute('wcs_cantine_todayList', array('schoolId' => $schoolId));
        }

        if ($request->getMethod() == 'POST') {
            $forms["statusType"]->handleRequest($request);
            $datas = $forms["statusType"]["status"]->getData();
            foreach (explode(',', $datas) as $id)
            {
                if ($id != '') {
                    $em = $this->getDoctrine()->getManager();
                    $lunches = $em->getRepository('WCSCantineBundle:Lunch')->find($id);
                    $lunches->setStatus('2');
                }
            }
            $em->flush();
            return $this->redirectToRoute('wcs_gesty_schools', array('schoolId' => $schoolId));
        }
        return null;
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $lunch = $em->getRepository('WCSCantineBundle:Lunch')->find($id);
        $em->remove($lunch);
        $em->flush();

        return $this->redirectToRoute('wcs_cantine_todayList',
            array('schoolId' => $lunch->getEleve()->getDivision()->getSchool()->getId())
        );
    }

    public function commandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $getNextWeekMealsNumber = $em->getRepository('WCSCantineBundle:Lunch')->getNextWeekMeals();
        $mealsNumber = array_sum($getNextWeekMealsNumber);

        $getNextWeekMealsNumberWithoutPork = $em->getRepository('WCSCantineBundle:Lunch')->getNextWeekMealsWithoutPork();
        $mealsNumberWithoutPork = array_sum($getNextWeekMealsNumberWithoutPork);

        //by default strtotime('last monday') returns the current day on mondays
        $firstDay = date('Y-m-d', strtotime('next monday'));
        $lastDay = date('Y-m-d', strtotime($firstDay.'+ 4 DAY'));

        $lunch = new Lunch();
        $form = $this->createForm(new LunchType(), $lunch);
        $form->handleRequest($request);

        return $this->render('WCSCantineBundle:Eleve:commande.html.twig', array(
            'mealsNumber' => $mealsNumber,
            'mealsNumberWithoutPork' => $mealsNumberWithoutPork,
            'firstDay' => $firstDay,
            'lastDay' => $lastDay,

        ));
    }
}
