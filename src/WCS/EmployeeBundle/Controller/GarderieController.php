<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use WCS\EmployeeBundle\Form\Type\GarderiePresentType;
use WCS\CantineBundle\Entity\School;
use WCS\CantineBundle\Entity\Garderie;


/**
 * List controller.
 *
 */
class GarderieController extends ActivityBaseController
{

    /**
     * CanteenController constructor.
     */
    public function __construct()
    {
        $this->setActivityRepositoryName('WCS\CantineBundle\Entity\Garderie');
        $this->setViewTodayList('WCSEmployeeBundle::garderieTodayList.html.twig');
    }

    /**
     * @param School $school
     * @return mixed
     */
    protected function createForms(School $school)
    {
        $data["entity"] = new Garderie();
        $data["forms"]["action"] = $this->form_action;

        $data["forms"]["listType"]  = $this->createForm(
            new GarderiePresentType(),
            $data["entity"],
            array(
                'action'=> $this->generateUrl($this->form_action, array('id'=>$school->getId())),
                'schoolId' => $school->getId())
        );

        return $data;
    }

    /**
     * @param School $school
     * @param $entity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function saveActivity(School $school, $entity)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity->setDateHeure(new \DateTime());
        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('wcs_employee_todaylist_garderie', array('id' => $school->getId()));
    }
}
