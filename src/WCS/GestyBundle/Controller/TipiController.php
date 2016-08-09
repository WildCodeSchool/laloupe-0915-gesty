<?php

namespace WCS\GestyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WCS\GestyBundle\Form\Model\TipiModel;
use WCS\GestyBundle\Form\Type\TipiType;

class TipiController extends Controller
{


    public function formAction(Request $request)
    {
        $tipiModel = new TipiModel();
        $form = $this->createForm(new TipiType(), $tipiModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $alert = NULL;
            /* R&eacute;cup&eacute;ration des valeurs des champs du formulaire */
            if (get_magic_quotes_gpc())
            {
                $date	     	= stripslashes(trim($tipiModel->getDate()));
                $montant		= stripslashes(trim(str_replace(",","",number_format($tipiModel->getMontant(), 2, ',', ' '))));
                $mel	     	= stripslashes(trim($tipiModel->getMel()));
                $refdet	= stripslashes(trim($tipiModel->getRefdet()));
            }
            else
            {
                $date	     	= trim($tipiModel->getDate());
                $montant		= trim(str_replace(",","",number_format($tipiModel->getMontant(), 2, ',', ' ')));
                $mel	     	= trim($tipiModel->getMel());
                $refdet	= trim($tipiModel->getRefdet());
            }

            /* Expression r&eacute;gulière permettant de v&eacute;rifier qu'aucun
            * en-tête n'est ins&eacute;r&eacute; dans nos champs */
            $regex_head = '/[\n\r]/';

            /* On v&eacute;rifie que tous les champs sont remplis */
            if (empty($montant)
                || empty($mel)
                || empty($refdet)
                || empty($date))
            {
                $alert = 'Tous les champs doivent être renseignés';
            }
            /* On v&eacute;rifie qu'il n'y a aucun header dans les champs */
            if (preg_match($regex_head, $montant)
                || preg_match($regex_head, $mel)
                || preg_match($regex_head, $refdet)
                || preg_match($regex_head, $date))
            {
                $alert = 'En-têtes interdites dans les champs du formulaire';
            }
            if (!empty($alert)) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('alert', $alert)
                ;
            }
            else {
                return $this->redirect('https://www.tipi.budget.gouv.fr/tpa/paiement.web?numcli=012327&exer=\'.$date.\'&refdet=\'.$date.$refdet.\'&montant=\'.$montant.\'&mel=\'.$mel.\'&urlcl=http:/www.gesty.fr&saisie=A');
            }
        }


        return $this->render('WCSGestyBundle::tipi.html.twig', array(
            'form' => $form->createView()
        ));
    }
}