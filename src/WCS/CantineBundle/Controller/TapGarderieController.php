<?php
namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\VarDumper\VarDumper;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Service\GestyScheduler\ActivityType;
use WCS\CantineBundle\Service\GestyScheduler\DaysOfWeeks;
use WCS\CantineBundle\Form\Type\TapType;

use Scheduler\Component\DateContainer\Period;

class TapGarderieController extends Controller
{
    public function inscrireAction(Request $request, Eleve $eleve)
    {
        $current_date   = $this->get('wcs.datenow')->getDate();
        $scheduler      = $this->get('wcs.gesty.scheduler');
        $periode_tap    = $scheduler->getCurrentOrNextSchoolPeriod( $current_date );
        $first_day_available = $scheduler->getFirstAvailableDate( $current_date, ActivityType::GARDERIE_MORNING );

        // prépare la période à partir de "demain" jusqu'au dernier jour de la période de classe
        $periode_from_today = new Period(
            $first_day_available,
            $periode_tap->getLastDate()
        );

        $daysOfWeek = new DaysOfWeeks(
            $periode_from_today,
            $scheduler
        );


        // créé le formulaire associé à l'élève
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(
            new TapType( $em, $daysOfWeek ),
            $eleve, array(
                'action' => $this->generateUrl('tapgarderie_inscription', array("id"=>$eleve->getId())),
                'method' => 'POST'
        ));

        // traite les infos saisies dans le formulaire
        if ($this->processPostedForm($request, $form, $eleve, $periode_from_today)) {
            return $this->redirectToRoute('wcs_cantine_dashboard');
        }


        return $this->render(
            'WCSCantineBundle:TapGarderie:inscription.html.twig',
            array(
                "eleve" => $eleve,
                "periode_tap" => $periode_tap,
                "first_day_available" => $first_day_available,
                "usual_dayoff" => ActivityType::getAllUsualDaysOff(),
                "form" => $form->createView()
                )
        );
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param Eleve $eleve
     * @return bool
     */
    private function processPostedForm(Request $request, Form $form, Eleve $eleve, Period $periode)
    {
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isValid()) {

            $temporary_taps_to_persist = $eleve->getTaps();

            $repo = $em->getRepository('WCSCantineBundle:Eleve');
            $eleve_taps_periode = $repo->findAllTapsForPeriode($eleve, $periode);
            foreach($eleve_taps_periode as $item) {
                if (!$temporary_taps_to_persist->contains($item)) {
                    $em->remove($item);
                }
            }

            $temporary_garderies_to_persist = $eleve->getGarderies();

            $eleve_garderies_periode = $repo->findAllGarderiesForPeriode($eleve, $periode);
            foreach($eleve_garderies_periode as $item) {
                if (!$temporary_garderies_to_persist->contains($item)) {
                    $em->remove($item);
                }
            }

            $eleve->setTapgarderieSigned(true);

            $em->flush();

            return true;
        }

        return false;
    }
}
