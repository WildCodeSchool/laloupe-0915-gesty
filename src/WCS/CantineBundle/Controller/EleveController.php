<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\Handler\EleveHandler;
use WCS\CantineBundle\Form\Model\EleveNew;
use WCS\CantineBundle\Form\Type\EleveEditType;
use WCS\CantineBundle\Form\Type\EleveType;
use WCS\CantineBundle\DependencyInjection\Ical;

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

        $vacancesHiver = $this->getHolidays('2016-02-06', '2016-02-22');
        $vacancesNoel = $this->getHolidays('2015-12-19', '2016-01-04');
        $vacancesToussaint = $this->getHolidays('2016-04-02', '2016-04-18');

        $icalVacancesEte = new \DateTime($this->getYearEnd());
        $grandesVacances = date_format($icalVacancesEte, ('Y-m-d'));

        $vacancesEte = new \DateTime($this->getYearEnd());
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
            'vacancesNoel' => $vacancesNoel,
            'grandesVacances' => $grandesVacances,
            'vacancesToussaint' => $vacancesToussaint,
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

        // Date du début et de fin des vacances de la Toussaint
        $toussaintStart = $this->getToussaintStart();
        $toussaintStartDT = new \DateTime($toussaintStart);
        $toussaintStartFormat = date_format($toussaintStartDT, ('Y-m-d'));
        $toussaintEnd = $this->getToussaintEnd();
        $toussaintEndDT = new \DateTime($toussaintEnd);
        $toussaintEndFormat = date_format($toussaintEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances de Noël
        $noelStart = $this->getNoelStart();
        $noelStartDT = new \DateTime($noelStart);
        $noelStartFormat = date_format($noelStartDT, ('Y-m-d'));
        $noelEnd = $this->getNoelEnd();
        $noelEndDT = new \DateTime($noelEnd);
        $noelEndFormat = date_format($noelEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances d'hiver
        $hiverStart = $this->getHiverStart();
        $hiverStartDT = new \DateTime($hiverStart);
        $hiverStartFormat = date_format($hiverStartDT, ('Y-m-d'));
        $hiverEnd = $this->getHiverEnd();
        $hiverEndDT = new \DateTime($hiverEnd);
        $hiverEndFormat = date_format($hiverEndDT, ('Y-m-d'));

        // Date du début et de fin des vacances de Printemps
        $printempsStart = $this->getPrintempsStart();
        $printempsStartDT = new \DateTime($printempsStart);
        $printempsStartFormat = date_format($printempsStartDT, ('Y-m-d'));
        $printempsEnd = $this->getPrintempsEnd();
        $printempsEndDT = new \DateTime($printempsEnd);
        $printempsEndFormat = date_format($printempsEndDT, ('Y-m-d'));

        $vacancesHiver = $this->getHolidays($hiverStartFormat, $hiverEndFormat);
        $vacancesNoel = $this->getHolidays($noelStartFormat, $noelEndFormat);
        $vacancesToussaint = $this->getHolidays($toussaintStartFormat, $toussaintEndFormat);
        $vacancesPrintemps = $this->getHolidays($printempsStartFormat, $printempsEndFormat);

        $jours= array('Lun','Mar','Mer','Jeu','Ven','Sam','Dim');

        $icalVacancesEte = new \DateTime($this->getYearEnd());
        $grandesVacances = date_format($icalVacancesEte, ('Y-m-d'));

        $vacancesEte = new \DateTime($this->getYearEnd());
        $date = date_timestamp_get($limit) + 168*60*60;
        $finAnnee = date_timestamp_get($vacancesEte);

        $cal = $this->getIcal();

        return $this->render('WCSCantineBundle:Eleve:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'calendrier' => $calendrier,
            'jours' => $jours,
            'dateLimit' => $date,
            'lunches' => $lunches,
            'finAnnee' => $finAnnee,
            'vacancesHiver' => $vacancesHiver,
            'vacancesToussaint' => $vacancesToussaint,
            'vacancesNoel' => $vacancesNoel,
            'vacancesPrintemps' => $vacancesPrintemps,
            'grandesVacances' => $grandesVacances,
            'cal' => $cal,
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
        $array = [];
        $period = new \DatePeriod(new \DateTime($start), new \DateInterval('P1D'), new \DateTime($end));

        foreach ($period as $date) {
            echo $array[] = date_format($date, ('Y-m-d'));
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

    public function getIcal()
    {
        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        return $ical->events();
    }

    // Get the date of the year end
    public function getYearEnd()
    {
        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();
        return $date = $array[6]['DTSTART'];
    }


    public function getToussaintStart()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));

        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();

        foreach ($array as $key => $value){
            foreach ($value as $test => $essai){
                if (strpos($essai, $anneeActuelle.'10') !== false and $test == 'DTSTART'){
                    return $essai;
                }
            }
        }
    }

    public function getToussaintEnd()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));

        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();

        foreach ($array as $key => $value){
            foreach ($value as $test => $essai){
                if (strpos($essai, $anneeActuelle.'11') !== false and $test == 'DTEND'){
                    return $essai;
                }
            }
        }
    }

    public function getNoelStart()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle.'12') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }

        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, ($anneeActuelle - 1).'12') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getNoelEnd()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();

        if ($moisActuel >= '07'){
            foreach ($array as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($anneeActuelle + 1).'01') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, $anneeActuelle.'01') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        }


    }

    public function getHiverStart()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($anneeActuelle + 1).'02') !== false and $test == 'DTSTART'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle . '02') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getHiverEnd()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value){
                foreach ($value as $test => $essai){
                    if (strpos($essai, ($anneeActuelle + 1).'02') !== false and $test == 'DTEND'){
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle . '02') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getPrintempsStart()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, ($anneeActuelle + 1). '04') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle. '04') !== false and $test == 'DTSTART') {
                        return $essai;
                    }
                }
            }
        }
    }

    public function getPrintempsEnd()
    {
        $now = new \DateTime();
        $anneeActuelle = date_format($now, ('Y'));
        $moisActuel = date_format($now, ('m'));

        $ical = new Ical("http://www.education.gouv.fr/download.php?file=http://cache.media.education.gouv.fr/ics/Calendrier_Scolaire_Zone_B.ics");
        $array = $ical->events();

        if ($moisActuel >= '07') {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, ($anneeActuelle + 1). '04') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        } else {
            foreach ($array as $key => $value) {
                foreach ($value as $test => $essai) {
                    if (strpos($essai, $anneeActuelle . '04') !== false and $test == 'DTEND') {
                        return $essai;
                    }
                }
            }
        }
    }





}
