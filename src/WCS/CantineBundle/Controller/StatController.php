<?php
namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;
use WCS\CantineBundle\Entity\User;
use WCS\CantineBundle\Entity\Eleve;

class StatController extends Controller
{

    public function countAction(Request $request)
    {
        // génère la liste des mois à sélectionner
        $current_date = $this->get('wcs.datenow')->getDate()->format('Y-m-d');

        $first_month = new \DateTime('2016-01-01');
        $last_month = new \DateTime( $current_date. ' this month');

        if ($last_month->format('m') != '07') {
            $last_month = new \DateTime($current_date.' last month');
        }

        $period = new \DatePeriod($first_month, new \DateInterval('P1M'), $last_month);


        // le dernier mois sera celui sélectionné par défaut dans le formulaire
        $month_selected = $last_month->format('Y-m');

        // change le mois sélectionné si une autre sélection a été postée depuis
        // le formulaire
        if ($request->isMethod('POST')) {
            $month_selected = $request->request->get('month_selected');
        }

        // charge la liste des stats pour le mois sélectionné
        $eleves = $this->loadStatsFromRepository($month_selected);


        return $this->render('WCSCantineBundle:Block:statmonthly.html.twig', array(
            'admin_pool' => $this->get('sonata.admin.pool'),
            'eleves'=>$eleves,
            'list_months' => $period,
            'month_selected' => $month_selected
        ));

    }

    private function loadStatsFromRepository($month)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('WCSCantineBundle:Eleve');
        
        $eleves=array();
        $dateStart = new \DateTime($month.'-01');
        $dateEnd = new \DateTime($month.'-01 last day of this month');

        
        $liste_eleves = $repo->findAll();
        
        foreach ($liste_eleves as $eleve){

            $tmp['eleve'] = $eleve;
            $tmp['total_cantine'] = $repo->findTotalCantineFor(
                $eleve,
                $dateStart,
                $dateEnd
            );

            $tmp['total_tapgarderie'] = $repo->findTotalTapGarderieFor(
                $eleve,
                $dateStart,
                $dateEnd
            );
            $eleves[] = $tmp;
            
        }
        return $eleves;
    }

    
}