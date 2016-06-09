<?php
namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\DataTransformer\DaysOfWeeks;
use WCS\CantineBundle\Form\Type\TapType;
use WCS\CalendrierBundle\Service\Periode\Periode;

class TapGarderieController extends Controller
{
    public function inscrireAction(Request $request, $id_eleve)
    {
        // récupère l'utilisateur actuellement connecté
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        // récupère une instance de Doctrine
        $em = $this->getDoctrine()->getManager();

        // récupère un  enfants
        $eleve = $em->getRepository("WCSCantineBundle:Eleve")->find($id_eleve);
        if (!$eleve || !$eleve->isCorrectParentConnected($user)) {
            return $this->redirectToRoute('wcs_cantine_dashboard');
        }

        // récupère la période scolaires en classe à la date du jour
        $periodesScolaires = $this->get("wcs.calendrierscolaire")->getPeriodesAnneeRentreeScolaire();
        $periode_tap = $periodesScolaires->getCurrentOrNextPeriodeEnClasse();

        // récupère les days of week sélectionnés
        $periode_from_today = new Periode(
            $this->get("wcs.datenow")->getDate()->format('Y-m-d'),
            $periode_tap->getFin()
        );

        $daysOfWeek = new DaysOfWeeks(
            $periode_from_today,
            $this->get('wcs.daysoff')
        );



        // créé le formulaire associé à l'élève
        $form = $this->createForm(
                    new TapType( $em, $daysOfWeek ),
                    $eleve, array(
            'action' => $this->generateUrl('tapgarderie_inscription', array("id_eleve"=>$id_eleve)),
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
    private function processPostedForm(Request $request, Form $form, Eleve $eleve, Periode $periode)
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


            $em->flush();

            return true;
        }

        return false;
    }
}
