<?php

namespace WCS\CantineBundle\Controller;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\Type\EleveType;

/**
 * Eleve controller.
 *
 */
class EleveController extends Controller
{

    /**
     * Lists all Eleve entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WCSCantineBundle:Eleve')->findAll();

        return $this->render('WCSCantineBundle:Eleve:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Eleve entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Eleve();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('wcs_cantine_dashboard'));
        }
        // Lancement de la fonction calendrier
        $calendrier = $this->generateCalendar(new \DateTime('2015-09-01'), new \DateTime('2016-07-31'));
        $limit = new \DateTime();

        $vacancesEte = new \DateTime('2016-07-06');

        $vacancesHiver = $this->getHolidays('2016-02-02', '2016-02-21');

        $date = date_timestamp_get($limit) + 168*60*60;
        $finAnnee = date_timestamp_get($vacancesEte);

        $jours= array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');


        return $this->render('WCSCantineBundle:Eleve:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'calendrier' => $calendrier,
            'jours' => $jours,
            'dateLimit' => $date,
            'finAnnee' => $finAnnee,
            'vacancesHiver' => $vacancesHiver,
        ));
    }

    /**
     * Creates a form to create a Eleve entity.
     *
     * @param Eleve $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Eleve $entity)
    {
        $form = $this->createForm(new EleveType(), $entity, array(
            'action' => $this->generateUrl('eleve_create'),
            'method' => 'POST',
        ));


        return $form;
    }

    /**
     * Displays a form to create a new Eleve entity.
     *
     */
    public function newAction()
    {
        $entity = new Eleve();
        $form = $this->createCreateForm($entity);

        return $this->render('WCSCantineBundle:Eleve:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
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
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WCSCantineBundle:Eleve')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eleve entity.');
        }

        $editForm = $this->createEditForm($entity);



        return $this->render('WCSCantineBundle:Eleve:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),

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
        $form = $this->createForm(new EleveType(), $entity, array(
            'action' => $this->generateUrl('eleve_update', array('id' => $entity->getId())),
            'method' => 'PUT',
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

    /**
     * Generate calendar
     */
    public function generateCalendar(\DateTime $start, \DateTime $end)
    {
        $return = array();
        $calendrier = $start;

        while ($calendrier <= $end) {
            $y = date_format($calendrier, ('Y'));
            $n = date_format($calendrier, ('n'));
            $j = date_format($calendrier, ('j'));
            $w = str_replace('0', '7', date_format($calendrier, ('w')));
            $return[$y][$n][$j] = $w;
            $calendrier->add(new \DateInterval('P1D'));
        }
        return $return;
    }


    /**
     * Generate range date
     */
    private function getHolidays($start, $end)
    {
        $interval = new \DateInterval('P1D');

        $realEnd = new \DateTime($end);
        $realEnd->add($interval);

        $period = new \DatePeriod(
            new \DateTime($start),
            $interval,
            $realEnd
        );

        foreach ($period as $date) {
            $array[] = date_format($date, ('Y-n-j'));
        }

        return $array;
    }

    public function dashboardAction()
    {
        $user = $this->getUser();
        $moyendepaiement = $user->getmodeDePaiement();
        $children = $user->getEleves();
        //$em = $this->getDoctrine()->getManager();
       // $jour = $em->getRepository('WCSCantineBundle:Eleve')->findByDate($children);

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé pour cet id:');
        }
        if (!$children) {
            throw $this->createNotFoundException('Aucun enfant trouvé pour cet id:');
        }

        return $this->render('WCSCantineBundle:Eleve:dashboard.html.twig', array(
            'user' => $user,
            'children' => $children,
            'modeDePaiement' =>$moyendepaiement,
            //'jour'=> $jour,

        ));


    }
}
