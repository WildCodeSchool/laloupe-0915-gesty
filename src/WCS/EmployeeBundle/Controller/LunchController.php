<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class LunchController extends EmployeeController
{
    public function showOrdersAction(Request $request)
    {
        $repo = $this->getRepository('WCSCantineBundle:Lunch');

        $options = array(
            'date_day'          => $this->getDateDay(),
            'enable_next_week'  => true
        );

        $dates = $repo->getWeekDates($options);

        $options['without_pork'] = false;
        $lunchStatsRegular = $repo->getWeekMeals( $options );

        $options['without_pork'] = true;
        $lunchStatsWithoutPork = $repo->getWeekMeals( $options );

        return $this->render('WCSEmployeeBundle::orders.html.twig', array(
            'totalLunchesRegular'       => $lunchStatsRegular->getTotal(),
            'totalLunchesWithoutPork'   => $lunchStatsWithoutPork->getTotal(),
            'firstDay'                  => $dates['first_day'],
            'lastDay'                   => $dates['last_day'],
        ));
    }
}
