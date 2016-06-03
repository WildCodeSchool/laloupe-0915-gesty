<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use WCS\EmployeeBundle\Form\Type\TapPresentType;
use WCS\CantineBundle\Entity\School;
use WCS\CantineBundle\Entity\Tap;


/**
 * List controller.
 *
 */
class TapController extends ActivityBaseController
{
    /**
     * CanteenController constructor.
     */
    public function __construct()
    {
        $this->type_activity = 'tap';
        $this->form_action = 'wcs_employee_todaylist_tap';
        $this->setActivityRepositoryName('WCS\CantineBundle\Entity\Tap');
        $this->setViewTodayList('WCSEmployeeBundle::tapTodayList.html.twig');
    }

    /**
     * @param School $school
     * @return mixed
     */
    protected function createForms(School $school)
    {
        $data["entity"] = new Tap();

        $data["forms"]["listType"]  = $this->createForm(
            new TapPresentType(),
            $data["entity"],
            array('schoolId' => $school->getId())
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

        $entity->setDate(new \DateTime());
        $em->persist($entity);
        $em->flush();

        return $this->redirectToRoute('wcs_employee_todaylist_tap', array('id' => $school->getId()));
    }
}
