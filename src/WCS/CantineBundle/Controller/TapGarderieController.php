<?php
namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use \WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\Type\TapType;


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
        $periode = $periodesScolaires->findEnClasseFrom(new \DateTime());

        // créé le formulaire associé à l'élève
        $form = $this->createForm(
                    new TapType( $em, $periode ),
                    $eleve, array(
            'action' => $this->generateUrl('tapgarderie_inscription', array("id_eleve"=>$id_eleve)),
            'method' => 'POST'
        ));

        // traite les infos saisies dans le formulaire
        if ($this->processPostedForm($request, $form, $eleve)) {
            return $this->redirectToRoute('wcs_cantine_dashboard');
        }


        return $this->render(
            'WCSCantineBundle:TapGarderie:inscription.html.twig',
            array(
                "eleve" => $eleve,
                "periode_tap" => $periode,
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
    private function processPostedForm(Request $request, Form $form, Eleve $eleve)
    {
        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);
        if ($form->isValid()) {

            $tapCurrents = $em->getRepository("WCSCantineBundle:Tap")->findByEleve($eleve);
            foreach($tapCurrents as $tap) {
                $em->remove($tap);
            }

            $garderieCurrents = $em->getRepository("WCSCantineBundle:Garderie")->findByEleve($eleve);
            foreach($garderieCurrents as $garderie) {
                $em->remove($garderie);
            }

            $em->flush();

            return true;
        }

        return false;
    }
}
