<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class LunchController extends ActivityControllerBase
{
    public function showOrdersAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('WCSCantineBundle:Lunch');

        $options = array(
            'date_day'          => $this->getDateDay(),
            'enable_next_week'  => true
        );

        // current week statistics
        $options['enable_next_week'] = false;

        $options['without_pork'] = false;
        $statsWeek['currentWeekMealsRegular'] = $repo->getWeekMeals( $options );

        $options['without_pork'] = true;
        $statsWeek['currentWeekMealsNoPork'] = $repo->getWeekMeals( $options );

        // next week statistics

        $options['enable_next_week'] = true;

        $options['without_pork'] = false;
        $statsWeek['nextWeekMealsRegular'] = $repo->getWeekMeals( $options );

        $options['without_pork'] = true;
        $statsWeek['nextWeekMealsNoPork'] = $repo->getWeekMeals( $options );

        return $this->render('WCSEmployeeBundle::orders.html.twig', array(
            'statsWeek'   => $statsWeek
        ));
    }
}
