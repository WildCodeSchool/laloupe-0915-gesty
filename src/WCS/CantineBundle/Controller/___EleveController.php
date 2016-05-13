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
        // Enregistre les élèves en BDD
        $entity = new EleveNew();
        $form = $this->createCreateForm($entity);
        $handler = new EleveHandler($form, $request, $this->getDoctrine()->getManager(), $this->getUser());
        if ($handler->process($entity)) {
            return $this->redirect($this->generateUrl('wcs_cantine_dashboard'));
        }

        // Lancement de la fonction calendrier
        $calendrier = $this->generateCalendar(new \DateTime('2015-09-01'), new \DateTime('2016-07-31'));
        $limit = new \DateTime();

        // Liste des jours de la semaine
        $jours= array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');

        // Récupération des dates du calendrier

        // Récupère les jours fériés en base de données
        $dateNow = new \DateTime('Y');
        $dateString = date_format($dateNow, ('Y'));
        $em = $this->getDoctrine()->getManager();
        $feries = $em->getRepository('WCSCantineBundle:Feries')->findBy(array('annee' => $dateString));
        $feriesArray = [];
        for ($i = 0; $i < count($feries); $i++){
            $feriesArray[$i]['paques'] = $feries[$i]->getPaques();
            $feriesArray[$i]['pentecote'] = $feries[$i]->getPentecote();
            $feriesArray[$i]['ascension'] = $feries[$i]->getAscension();
            // pensez a rajouter le vendredi de l'ascension //
        }
        // fin //

        // Date du début et de fin des vacances de la Toussaint
        $toussaintStart = $this->container->get('calendar.holidays')->getToussaintStart();
        $toussaintStartDT = new \DateTime($toussaintStart);
        $toussaintStartFormat = date_format($toussaintStartDT, ('Y-m-d'));
        $toussaintEnd = $this->container->get('calendar.holidays')->getToussaintEnd();
        $toussaintEndDT = new \DateTime($toussaintEnd);
        $toussaintEndFormat = date_format($toussaintEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances de Noël
        $noelStart = $this->container->get('calendar.holidays')->getNoelStart();
        $noelStartDT = new \DateTime($noelStart);
        $noelStartFormat = date_format($noelStartDT, ('Y-m-d'));
        $noelEnd = $this->container->get('calendar.holidays')->getNoelEnd();
        $noelEndDT = new \DateTime($noelEnd);
        $noelEndFormat = date_format($noelEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances d'hiver
        $hiverStart = $this->container->get('calendar.holidays')->getHiverStart();
        $hiverStartDT = new \DateTime($hiverStart);
        $hiverStartFormat = date_format($hiverStartDT, ('Y-m-d'));
        $hiverEnd = $this->container->get('calendar.holidays')->getHiverEnd();
        $hiverEndDT = new \DateTime($hiverEnd);
        $hiverEndFormat = date_format($hiverEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances de Printemps
        $printempsStart = $this->container->get('calendar.holidays')->getPrintempsStart();
        $printempsStartDT = new \DateTime($printempsStart);
        $printempsStartFormat = date_format($printempsStartDT, ('Y-m-d'));
        $printempsEnd = $this->container->get('calendar.holidays')->getPrintempsEnd();
        $printempsEndDT = new \DateTime($printempsEnd);
        $printempsEndFormat = date_format($printempsEndDT, ('Y-m-d'));

        $vacancesHiver = $this->getHolidays($hiverStartFormat, $hiverEndFormat);
        $vacancesNoel = $this->getHolidays($noelStartFormat, $noelEndFormat);
        $vacancesToussaint = $this->getHolidays($toussaintStartFormat, $toussaintEndFormat);
        $vacancesPrintemps = $this->getHolidays($printempsStartFormat, $printempsEndFormat);

        $icalVacancesEte = new \DateTime($this->container->get('calendar.holidays')->getYearEnd());
        $grandesVacances = date_format($icalVacancesEte, ('Y-m-d'));

        $vacancesEte = new \DateTime($this->container->get('calendar.holidays')->getYearEnd());
        $date = date_timestamp_get($limit) + 168*60*60;
        $finAnnee = date_timestamp_get($vacancesEte);

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
        // Récupère la date d'aujourd'hui
        $dateNow = new \DateTime('Y');
        $dateString = date_format($dateNow, ('Y')); // Formate la date d'aujourd'hui en sélectionnant que l'année

        // Récupère les informations de l'élève
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('WCSCantineBundle:Eleve')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Eleve entity.');
        }

        // Récupère les jours habituels de cantine
        $entityHabits = $entity->getHabits();

        // Récupère les jours fériés en base de données
        $feries = $em->getRepository('WCSCantineBundle:Feries')->findBy(array('annee' => $dateString));
        // Boucle sur l'entité $feries pour la transformer en un array
        $feriesArray = [];
        for ($i = 0; $i < count($feries); $i++){
            $feriesArray[$i]['paques'] = $feries[$i]->getPaques();
            $feriesArray[$i]['pentecote'] = $feries[$i]->getPentecote();
            $feriesArray[$i]['ascension'] = $feries[$i]->getAscension();
        }
        // Fin //

        $editForm = $this->createEditForm($entity);

        // Lancement de la fonction calendrier
        $calendrier = $this->generateCalendar(new \DateTime('2015-09-01'), new \DateTime('2016-07-31'));
        $limit = new \DateTime();

        // Liste des jours de la semaine
        $jours= array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');

        // Date du début et de fin des vacances de la Toussaint
        $toussaintStart = $this->container->get('calendar.holidays')->getToussaintStart();
        $toussaintStartDT = new \DateTime($toussaintStart);
        $toussaintStartFormat = date_format($toussaintStartDT, ('Y-m-d'));
        $toussaintEnd = $this->container->get('calendar.holidays')->getToussaintEnd();
        $toussaintEndDT = new \DateTime($toussaintEnd);
        $toussaintEndFormat = date_format($toussaintEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances de Noël
        $noelStart = $this->container->get('calendar.holidays')->getNoelStart();
        $noelStartDT = new \DateTime($noelStart);
        $noelStartFormat = date_format($noelStartDT, ('Y-m-d'));
        $noelEnd = $this->container->get('calendar.holidays')->getNoelEnd();
        $noelEndDT = new \DateTime($noelEnd);
        $noelEndFormat = date_format($noelEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances d'hiver
        $hiverStart = $this->container->get('calendar.holidays')->getHiverStart();
        $hiverStartDT = new \DateTime($hiverStart);
        $hiverStartFormat = date_format($hiverStartDT, ('Y-m-d'));
        $hiverEnd = $this->container->get('calendar.holidays')->getHiverEnd();
        $hiverEndDT = new \DateTime($hiverEnd);
        $hiverEndFormat = date_format($hiverEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances de Printemps
        $printempsStart = $this->container->get('calendar.holidays')->getPrintempsStart();
        $printempsStartDT = new \DateTime($printempsStart);
        $printempsStartFormat = date_format($printempsStartDT, ('Y-m-d'));
        $printempsEnd = $this->container->get('calendar.holidays')->getPrintempsEnd();
        $printempsEndDT = new \DateTime($printempsEnd);
        $printempsEndFormat = date_format($printempsEndDT, ('Y-m-d'));

        // Récupération de toutes les dates entre deux dates
        $vacancesHiver = $this->getHolidays($hiverStartFormat, $hiverEndFormat);
        $vacancesNoel = $this->getHolidays($noelStartFormat, $noelEndFormat);
        $vacancesToussaint = $this->getHolidays($toussaintStartFormat, $toussaintEndFormat);
        $vacancesPrintemps = $this->getHolidays($printempsStartFormat, $printempsEndFormat);

        // Récupération de la date de fin d'année dans l'ICAL
        $icalVacancesEte = new \DateTime($this->container->get('calendar.holidays')->getYearEnd());
        $grandesVacances = date_format($icalVacancesEte, ('Y-m-d'));

        $vacancesEte = new \DateTime($this->container->get('calendar.holidays')->getYearEnd());
        $date = date_timestamp_get($limit) + 168*60*60;
        $finAnnee = date_timestamp_get($vacancesEte);

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
        $array = [];
        $period = new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), new \DateTime($end));

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
