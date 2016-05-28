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
     */
    public function todayListAction(Request $request, $schoolId)
    {
        $dateNow = new \DateTime();

        $em = $this->getDoctrine()->getManager();
        $lunches = $em->getRepository('WCSCantineBundle:Lunch')->getTodayList($schoolId);

        $school = $em->getRepository('WCSCantineBundle:School')->find($schoolId);

        $lunch = new Lunch();
        $form = $this->createForm(new LunchType(), $lunch, array('schoolId' => $schoolId));
        $statusForm = $this->createForm(new StatusType(), $lunch);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $lunch->setDate($dateNow);
            $em->persist($lunch);
            $em->flush();

            return $this->redirect($this->generateUrl('wcs_cantine_todayList', array('schoolId' => $schoolId)));
        }

        if ($request->getMethod() == 'POST') {
            $statusForm->handleRequest($request);
            $datas = $statusForm["status"]->getData();
            foreach (explode(',', $datas) as $id)
            {
                if ($id != '') {
                    $em = $this->getDoctrine()->getManager();
                    $lunches = $em->getRepository('WCSCantineBundle:Lunch')->find($id);
                    $lunches->setStatus('2');
                }
            }
            $em->flush();
            return $this->redirect($this->generateUrl('wcs_gesty_ecoles', array('schoolId' => $schoolId)));
        }


        return $this->render('WCSCantineBundle:Eleve:todayList.html.twig', array(
            'lunches' => $lunches,
            'form' => $form->createView(),
            'statusForm' => $statusForm->createView(),
            'school' => $school
        ));

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
