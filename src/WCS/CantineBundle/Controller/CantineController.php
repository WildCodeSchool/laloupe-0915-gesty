<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\Sonata\UserBundle\Entity\User;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\Handler\EleveHandler;
use WCS\CantineBundle\Form\Model\EleveNew;
use WCS\CantineBundle\Form\Type\EleveEditType;
use WCS\CantineBundle\Form\Type\EleveType;

/**
 * Eleve controller.
 *
 */
class CantineController extends Controller
{
    public function inscrireAction($id_eleve)
    {

    }
    /**
     * Creates a new Eleve entity.
     *
     */
    public function createAction(Request $request)
    {
        // Enregistre les élèves en BDD
        $entity = new EleveNew();
        $form = $this->createCreateForm($entity);
        $handler = new EleveHandler($form, $request, $this->getDoctrine()->getManager(), $this->getUser());
        if ($handler->process($entity)) {
            return $this->redirect($this->generateUrl('wcs_cantine_dashboard'));
        }

        return $this->render('WCSCantineBundle:Eleve:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'calendrier' => $calendrier,
            'jours' => $jours,
            'dateLimit' => $date,
            'finAnnee' => $finAnnee,
            'vacancesHiver' => $vacancesHiver,
            'vacancesNoel' => $vacancesNoel,
            'grandesVacances' => $grandesVacances,
            'vacancesToussaint' => $vacancesToussaint,
            'vacancesPrintemps' => $vacancesPrintemps,
            'feries' => $feriesArray,
        ));
    }

    /**
     * Creates a form to create a Eleve entity.
     *
     * @param EleveNew $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(EleveNew $entity)
    {
        $form = $this->createForm(new EleveType(), $entity, array(
            'action' => $this->generateUrl('eleve_create'),
            'method' => 'POST',
        ));


        return $form;
    }

    /**
     * Finds and displays a Eleve entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WCSCantineBundle:Eleve')->find($id);


        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eleve entity.');
        }


        return $this->render('WCSCantineBundle:Eleve:show.html.twig', array(
            'entity' => $entity,
        ));
    }

    /**
     * Displays a form to edit an existing Eleve entity.
     *
     */
    public function editAction($id)
    {
        // Récupère les informations de l'élève
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WCSCantineBundle:Eleve')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eleve entity.');
        }
        $editForm = $this->createEditForm($entity);


        // Récupère les jours habituels de cantine
        $entityHabits = $entity->getHabits();
        
        // lunch dates as string
        $lunchDates = '';
        foreach ($entity->getLunches() as $lunch)
        {
            $lunchDates .= $lunch->getStringDate();
        }

        return $this->render('WCSCantineBundle:Eleve:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'calendrier' => $calendrier,
            'jours' => $jours,
            'dateLimit' => $date,
            'finAnnee' => $finAnnee,
            'vacancesHiver' => $vacancesHiver,
            'vacancesToussaint' => $vacancesToussaint,
            'vacancesNoel' => $vacancesNoel,
            'vacancesPrintemps' => $vacancesPrintemps,
            'grandesVacances' => $grandesVacances,
            'feries' => $feriesArray,
            'habits' => $entityHabits,
            'lunchDates' => $lunchDates
        ));
    }

    /**
     * Creates a form to edit a Eleve entity.
     *
     * @param Eleve $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Eleve $entity)
    {
        $form = $this->createForm(new EleveEditType($this->getDoctrine()->getManager()), $entity, array(
            'action' => $this->generateUrl('eleve_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Edits an existing Eleve entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WCSCantineBundle:Eleve')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eleve entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);



        if ($editForm->isValid()) {
            $oldLunches = $em->getRepository('WCSCantineBundle:Lunch')->findByEleve($entity);
            foreach($oldLunches as $lunch)
            {
                if (!$entity->getLunches()->contains($lunch))
                    $em->remove($lunch);
            }
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('wcs_cantine_dashboard', array('id' => $id)));
        }


        return $this->render('WCSCantineBundle:Eleve:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Eleve entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WCSCantineBundle:Eleve')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Eleve entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('eleve'));
    }

    /**
     * Creates a form to delete a Eleve entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eleve_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))

            ->getForm();

    }

    public function dashboardAction(Request $request)
    {
        $user = $this->getUser();
        $moyendepaiement = $user->getmodeDePaiement();
        $children = $user->getEleves();


        $em = $this->getDoctrine()->getManager();
        $presentChildren = $em->getRepository('WCSCantineBundle:Eleve')->findOneBy(array('user' => $user->getId()));
        $files = $em->getRepository('ApplicationSonataUserBundle:User')->findBy(array(
            'id' => $user->getId(),
        ));
        $filesArray = [];
        /*
                for ($i = 0; $i < count($files); $i++){
                    $filesArray[$i]['Justificatif de domicile'] = $files[$i]->getPathDomicile();
                    $filesArray[$i]['Justificatif de prestations CAF'] = $files[$i]->getPathPrestations();
                    $filesArray[$i]['Justificatif de salaire 1'] = $files[$i]->getPathSalaire1();
                    $filesArray[$i]['Justificatif de salaire 2'] = $files[$i]->getPathSalaire2();
                    $filesArray[$i]['Justificatif de salaire 3'] = $files[$i]->getPathSalaire3();

                }
        */

        for ($i = 0; $i < count($files); $i++){
            $filesArray[$i][User::type_Domicile]                = 'Justificatif de domicile';
            $filesArray[$i][User::type_Prestations]             = 'Justificatif de prestations CAF';
            $filesArray[$i][User::type_Salaire1]                = 'Justificatif de salaire 1';
            $filesArray[$i][User::type_Salaire2]                = 'Justificatif de salaire 2';
            $filesArray[$i][User::type_Salaire3]                = 'Justificatif de salaire 3';
        }

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé pour cet id:');
        }


        return $this->render('WCSCantineBundle:Eleve:dashboard.html.twig', array(
            'user' => $user,
            'children' => $children,
            'modeDePaiement' => $moyendepaiement,
            'presentChildren' => $presentChildren,
            'files'=>$filesArray,

        ));


    }


    public function updateDate($query)
    {
        return $this->getDoctrine()->getManager()
            ->createQuery(
                'UPDATE WCSCantineBundle:Eleve SET dates'
            )
            ->getResult();
    }
}
