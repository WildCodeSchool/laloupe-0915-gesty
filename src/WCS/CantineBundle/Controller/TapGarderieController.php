<?php
namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Service\GestyScheduler\ActivityType;
use WCS\CantineBundle\Service\GestyScheduler\DaysOfWeeks;
use WCS\CantineBundle\Form\Type\TapGarderieType;

use Scheduler\Component\DateContainer\Period;

class TapGarderieController extends Controller
{
    public function inscrireAction(Request $request, Eleve $eleve)
    {
        $current_date        = $this->get('wcs.datenow')->getDate();
        $scheduler           = $this->get('wcs.gesty.scheduler');
        $period_school_year  = $scheduler->getCurrentOrNextSchoolYear($current_date);
        $first_day_available = $scheduler->getFirstAvailableDate( $current_date, ActivityType::GARDERIE_MORNING );
        $period_inclass      = $scheduler->getCurrentOrNextSchoolPeriod( $first_day_available );
        // pour les inscriptions :
        // prépare la période à partir de "demain" jusqu'au dernier jour de l'année scolaire
        $period_subscribes = new Period(
            $first_day_available,
            $period_school_year->getLastDate()
        );

        // créé le formulaire associé à l'élève
        $form = $this->createForm(
            new TapGarderieType(),
            $eleve, array(
                'action'        => $this->generateUrl('tapgarderie_inscription', array("id"=>$eleve->getId())),
                'method'        => 'POST',
                'manager'       => $this->getDoctrine()->getManager(),
                'days_of_week'  => new DaysOfWeeks(
                    $period_subscribes,
                    $scheduler
                )
        ));

        // traite les infos saisies dans le formulaire
        if ($this->processPostedForm($request, $form, $eleve, $period_subscribes)) {
            return $this->redirectToRoute('wcs_cantine_dashboard');
        }


        return $this->render(
            'WCSCantineBundle:TapGarderie:inscription.html.twig',
            array(
                "eleve" => $eleve,
                "period_inclass" => $period_inclass,
                "period_subscribes" => $period_subscribes,
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

            // retire toutes les inscriptions au TAP pour la période qui ne sont pas
            // et qui n'ont pas été enregistré par le parent
            // dans la nouvelle sélection
            $repo = $em->getRepository('WCSCantineBundle:Eleve');
            $eleve_taps_periode = $repo->findAllTapsForPeriode($eleve, $periode, true);
            foreach($eleve_taps_periode as $item) {
                if (!$temporary_taps_to_persist->contains($item)) {
                    $em->remove($item);
                }
            }

            // retire toutes les inscriptions à la garderie pour la période qui ne sont pas
            // et qui n'ont pas été enregistré par le parent
            // dans la nouvelle sélection
            $temporary_garderies_to_persist = $eleve->getGarderies();

            $eleve_garderies_periode = $repo->findAllGarderiesForPeriode($eleve, $periode, true);
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
