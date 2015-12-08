<?php

namespace Application\Sonata\UserBundle\Controller;

use Application\Sonata\UserBundle\Form\Type\ProfileType;
use Sonata\UserBundle\Controller\ProfileFOSUser1Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;


class ProfileController extends BaseController
{
    public function setProfileAction(Request $request)
    {
        $entity = $this->getUser();
        $form    = $this->createForm(new ProfileType(), $entity);

        $form->handleRequest($request);


        // Pour un double submit
        /*$form = $this->createFormBuilder($task, array(
            'validation_groups' => array('registration'),))
            ->add('codePostal', 'integer')
            ->add('telephone', 'integer')
            ->add('telephoneSecondaire', 'integer')
            ->add('CAF', 'text')
            ->add('NumeroIban', 'text');*/

        if ($form->isValid()) {


            // On enregistre le message crypté dans la base de données
            $em = $this->getDoctrine()->getManager();

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('eleve_create'));
        }


        // si un formulaire contient plus d'un bouton submit mettre le elseif

        /*elseif ($form->isValid()) {
        $nextAction = $form->get('saveAndAdd')->isClicked()
            ? 'task_new'
            : 'task_success';

        return $this->redirect($this->generateUrl($nextAction));
        */

        return $this->render('SonataUserBundle:Profile:show.html.twig',array(
            'form' => $form->createView(),
            ));
    }

}
