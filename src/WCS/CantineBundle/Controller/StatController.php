<?php
namespace WCS\CantineBundle\Controller;

use Scheduler\Component\DateContainer\Period;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\ArchiveStat;

class StatController extends Controller
{

    public function countAction(Request $request)
    {
        $period = $this->getSelectedMonth($request);

        // le dernier mois sera celui sélectionné par défaut dans le formulaire
        $month_selected = $period->getLastDate()->format('Y-m');

        // change le mois sélectionné si une autre sélection a été postée depuis
        // le formulaire
        if ($request->isMethod('POST')) {
            $month_selected = $request->request->get('month_selected');
        }

        // charge la liste des stats pour le mois sélectionné
        $stats = $this->getDoctrine()
            ->getManager()
            ->getRepository('WCSCantineBundle:ArchiveStat')
            ->getStatsFromRepository($month_selected);


        return $this->render('WCSCantineBundle:Block:statmonthly.html.twig', array(
            'admin_pool'        => $this->get('sonata.admin.pool'),
            'stats'             => $stats,
            'list_months'       => $period->getMonthIterator(),
            'month_selected'    => $month_selected
        ));

    }

    /**
     * @param Request $request
     * @return Period
     */
    private function getSelectedMonth(Request $request)
    {
        // génère la liste des mois à sélectionner
        $current_date = $this->get('wcs.datenow')->getDate()->format('Y-m-d');

        $first_month    = new \DateTime('2016-01-01');
        $last_month     = new \DateTime( $current_date. ' this month');

        if ($last_month->format('m') != '07') {
            $last_month = new \DateTime($current_date.' last month');
        }

        $period = new Period($first_month, $last_month);
        return $period;
        //return new \DatePeriod($first_month, new \DateInterval('P1M'), $last_month);
    }
    
}