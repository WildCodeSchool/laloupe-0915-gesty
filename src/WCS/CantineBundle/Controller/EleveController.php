<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\Handler\EleveHandler;
use WCS\CantineBundle\Form\Model\EleveNew;
use WCS\CantineBundle\Form\Type\EleveEditType;
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
        $entity = new EleveNew();
        $form = $this->createCreateForm($entity);
        $handler = new EleveHandler($form, $request, $this->getDoctrine()->getManager(), $this->getUser());

        if ($handler->process($entity)) {
            return $this->redirect($this->generateUrl('wcs_cantine_dashboard'));
        }
        // Lancement de la fonction calendrier
        $calendrier = $this->generateCalendar(new \DateTime('2015-09-01'), new \DateTime('2016-07-31'));
        $limit = new \DateTime();

        // Récupération des dates du calendrier

        $em = $this->getDoctrine()->getManager();
        $cal = $em->getRepository('WCSCantineBundle:Calendar')->findAll();

        $vacancesHiver = $this->getHolidays('2016-02-06', '2016-02-22');
        $vacancesNoel = $this->getHolidays('2015-12-19', '2016-01-04');
        $vacancesToussaint = $this->getHolidays('2016-04-02', '2016-04-18');

        $vacancesEte = new \DateTime('2016-07-06');
        $date = date_timestamp_get($limit) + 168*60*60;
        $finAnnee = date_timestamp_get($vacancesEte);

        $grandesVacances = '2016-07-06';

        $jours= array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');

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
            'cal' => $cal,
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
        $lunches = $em->getRepository('WCSCantineBundle:Lunch')->findBy(array('eleve' => $entity));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eleve entity.');
        }

        $editForm = $this->createEditForm($entity);

        // Lancement de la fonction calendrier
        $calendrier = $this->generateCalendar(new \DateTime('2015-09-01'), new \DateTime('2016-07-31'));
        $limit = new \DateTime();

        $vacancesHiver = $this->getHolidays('2016-02-06', '2016-02-22');
        $vacancesNoel = $this->getHolidays('2015-12-19', '2016-01-04');
        $vacancesToussaint = $this->getHolidays('2016-04-02', '2016-04-18');

        $vacancesEte = new \DateTime('2016-07-06');
        $date = date_timestamp_get($limit) + 168*60*60;
        $finAnnee = date_timestamp_get($vacancesEte);

        $jours= array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');

        $grandesVacances = '2016-07-06';

        return $this->render('WCSCantineBundle:Eleve:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'calendrier' => $calendrier,
            'jours' => $jours,
            'dateLimit' => $date,
            'finAnnee' => $finAnnee,
            'vacancesHiver' => $vacancesHiver,
            'lunches' => $lunches,
            'grandesVacances' => $grandesVacances,
            'vacancesToussaint' => $vacancesToussaint,
            'vacancesNoel' => $vacancesNoel,
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
        $form = $this->createForm(new EleveEditType(), $entity, array(
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
            $m = date_format($calendrier, ('m'));
            $d = date_format($calendrier, ('d'));
            $w = str_replace('0', '7', date_format($calendrier, ('w')));
            $return[$y][$m][$d] = $w;
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

        $period = new \DatePeriod(
            new \DateTime($start),
            $interval,
            $realEnd
        );

        foreach ($period as $date) {
            $array[] = date_format($date, ('Y-m-d'));
        }

        return $array;
    }

    public function dashboardAction(Request $request)
    {
        $user = $this->getUser();
        $moyendepaiement = $user->getmodeDePaiement();
        $children = $user->getEleves();

        $em = $this->getDoctrine()->getManager();
        $presentChildren = $em->getRepository('WCSCantineBundle:Eleve')->findOneBy(array('user' => $user->getId()));

        if (!$user) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé pour cet id:');
        }


        return $this->render('WCSCantineBundle:Eleve:dashboard.html.twig', array(
            'user' => $user,
            'children' => $children,
            'modeDePaiement' => $moyendepaiement,
            'presentChildren' => $presentChildren,
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

    public function getHolidaysDates()
    {
        return $this->getDoctrine()->getManager()
            ->createQuery(
                'SELECT e FROM WCSCantineBundle:Calendar e'
            )
            ->getResult();
    }

}
