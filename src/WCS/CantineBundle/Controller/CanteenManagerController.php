<?php
// src/WCS/CantineBundle/Controller/CanteenManagerController.php

namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use WCS\CantineBundle\Form\Type\StatusType;
use WCS\CantineBundle\Form\Type\LunchType;
use WCS\CantineBundle\Entity\Lunch;

/**
 * List controller.
 *
 */
class CanteenManagerController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $schools = $em->getRepository('WCSCantineBundle:School')->findAll();

        return $this->render('WCSCantineBundle:CanteenManager:index.html.twig', array(
            'schools' => $schools,
        ));
    }

    /**
     * Lists all Eleve entities.
     *
     * @param Request $request
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function todayListAction(Request $request, $schoolId)
    {
        $em = $this->getDoctrine()->getManager();
        $lunches = $em->getRepository('WCSCantineBundle:Lunch')->getTodayList($schoolId);
        $school = $em->getRepository('WCSCantineBundle:School')->find($schoolId);

        $lunch = new Lunch();
        $forms["lunchType"]  = $this->createForm(new LunchType(), $lunch, array('schoolId' => $schoolId));
        $forms["statusType"] = $this->createForm(new StatusType(), $lunch);

        $resp = $this->processTodayListForm($request, $forms, $lunch, $schoolId);
        if (!is_null($resp)) {
            return $resp;
        }

        return $this->render('WCSCantineBundle:Eleve:todayList.html.twig', array(
            'lunches' => $lunches,
            'form' => $forms["lunchType"]->createView(),
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
    private function processTodayListForm(Request $request, $forms, $lunch, $schoolId)
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
            return $this->redirectToRoute('wcs_gesty_ecoles', array('schoolId' => $schoolId));
        }
        return null;
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $lunch = $em->getRepository('WCSCantineBundle:Lunch')->find($id);
        $em->remove($lunch);
        $em->flush();

        return $this->redirect($this->generateUrl('wcs_cantine_todayList', array('schoolId' => $lunch->getEleve()->getDivision()->getSchool()->getId())));
    }

    public function commandeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $getNextWeekMealsNumber = $em->getRepository('WCSCantineBundle:Lunch')->getNextWeekMeals();
        $mealsNumber = array_sum($getNextWeekMealsNumber);

        $getNextWeekMealsNumberWithoutPork = $em->getRepository('WCSCantineBundle:Lunch')->getNextWeekMealsWithoutPork();
        $mealsNumberWithoutPork = array_sum($getNextWeekMealsNumberWithoutPork);

        $firstDay = date('Y-m-d', strtotime('next monday')); //by default strtotime('last monday') returns the current day on mondays
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
