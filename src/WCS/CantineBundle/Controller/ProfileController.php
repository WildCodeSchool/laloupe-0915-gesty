<?php

namespace WCS\CantineBundle\Controller;

use WCS\CantineBundle\Form\Type\ProfileType;
use Sonata\UserBundle\Controller\ProfileFOSUser1Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;


class ProfileController extends BaseController
{
    public function editAction(Request $request)
    {
        $entity = $this->getUser();
        $form    = $this->createForm(new ProfileType(), $entity);

        $em = $this->getDoctrine()->getManager();

        $form->handleRequest($request);

        if ($form->isValid()) {

            // On enregistre le message crypté dans la base de données
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('wcs_cantine_dashboard'));
        }

        // récupère le nombre d'enfants dont la classe propose un voyage scolaire à effectuer durant l'année scolaire

        $nbChildrenVoyageInscrits = $em->getRepository('WCSCantineBundle:Eleve')->findNbEnfantInscritsVoyage(
            $entity,
            $this->get('wcs.datenow')->getDate()
        );

        // si un formulaire contient plus d'un bouton submit mettre le elseif

        $request->getSession()
            ->getFlashBag()
            ->add('success1', 'Si 1ère inscription N\'oubliez pas d\'inscrire votre(vos) enfant(s) à l\'étape suivante svp!')
        ;

        return $this->render('WCSCantineBundle:Eleve:edit_parent.html.twig', //'SonataUserBundle:Profile:show.html.twig',
            array(
            'form' => $form->createView(),
            'entity' => $entity,
            'nbChildrenVoyageInscrits' => $nbChildrenVoyageInscrits
            ));
    }
}
