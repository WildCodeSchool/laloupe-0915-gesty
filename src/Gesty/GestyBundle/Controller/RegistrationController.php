<?php

namespace Gesty\GestyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gesty\GestyBundle\Entity\User;
use Gesty\GestyBundle\Form\Type\MessageType;
use Symfony\Component\HttpFoundation\Request;
use Gesty\GestyBundle\Entity\Formulaire;

class RegistrationController extends Controller
{
    public function registerAction(Request $request)
    {
        $task = new Formulaire();
        // $task->setformulaire('Write a blog post'); // nom du formulaire


        $form = $this->createFormBuilder($task)
            ->setMethod("POST")
            ->add('civilite','choice',array(
                'choices'   => array('0' => 'M.', '1' => 'Mme')))
            ->add('nom', 'text')
            ->add('prenom', 'text')
            ->add('adresse', 'text')
            ->add('codePostal', 'text')
            ->add('commune', 'text')
            ->add('telephone', 'text')
            ->add('telephoneSecondaire', 'text')
            ->add('caf', 'text')
            ->add('modeDePaiement', 'choice',array(
                'choices'   => array('0' => 'Chèque', '1' => 'Especes', '2' => 'Prélèvements')))
            ->add('numeroIban', 'text')
            ->add('mandatActif', 'checkbox')
            ->add('envoyer', 'submit')
            ->getForm();

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
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('gesty_page_foyer'));
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
