<?php
namespace WCS\EmployeeBundle\Controller;

use WCS\EmployeeBundle\Form\Type\StatusType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\School;


abstract class ActivityBaseController extends Controller
{
    protected $type_activity;
    protected $form_action='';

    /*========================================================================
        to implement in derived classes
    ========================================================================*/
    abstract protected function createForms(School $school);
    abstract protected function saveActivity(School $school, $entity);


    /*========================================================================
        Methods meant to be used by
    ========================================================================*/

    /**
     * Lists all Eleve entities.
     *
     * @param Request $request
     * @param $schoolId
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showTodayListAction(Request $request, School $school)
    {
        $em = $this->getDoctrine()->getManager();

        $entities   = $this->getActivityRepository()->getTodayList($school, $this->optionsTodayList);

        $data = $this->createForms($school);
        $data["forms"]["statusType"] = $this->createForm(new StatusType($this->activityRepositoryName), $data["entity"]);

        $resp = $this->processTodayListForm($request, $data["forms"], $data["entity"], $school);
        if (!is_null($resp)) {
            return $resp;
        }

        return $this->render($this->viewTodayList, array(
            'entities'      => $entities,
            'form'          => $data["forms"]["listType"]->createView(),
            'statusForm'    => $data["forms"]["statusType"]->createView(),
            'school'        => $school,
            'form_action'   => isset($data["forms"]["action"])?$data["forms"]["action"]:'',
            'type_activity' => $this->type_activity
        ));
    }


    /**
     * @param Request $request
     * @param array $forms
     * @param object $entity
     * @param School $school
     * @return null|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function processTodayListForm(Request $request, $forms, $entity, School $school)
    {
        $em = $this->getDoctrine()->getManager();
        $forms["listType"]->handleRequest($request);

        if ($forms["listType"]->isValid()) {
            return $this->saveActivity($school, $entity);
        }

        if ($request->getMethod() == 'POST') {
            $forms["statusType"]->handleRequest($request);
            $datas = $forms["statusType"]["status"]->getData();
            foreach (explode(',', $datas) as $id)
            {
                if ($id != '') {
                    $entity = $this->getActivityRepository()->find($id);
                    $entity->setStatus('2');
                }
            }
            $em->flush();
            return $this->redirectToRoute('wcs_employee_schools', array('id' => $school->getId()));
        }

        return null;
    }

    /**
     * @param integer $id_activity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(School $school, $id_activity)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getActivityRepository()->find($id_activity);
        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute($this->form_action, //'wcs_employee_todaylist_cantine',
//            array('schoolId' => $entity->getEleve()->getDivision()->getSchool()->getId())
            array('id' => $school->getId())
        );
    }



    /*========================================================================

    ========================================================================*/
    /**
     * @param mixed $activityRepositoryName
     */
    public function setActivityRepositoryName($activityRepositoryName)
    {
        $this->activityRepositoryName = $activityRepositoryName;
    }

    /**
     * @return \WCS\CantineBundle\Entity\ActivityRepositoryAbstract
     */
    protected function getActivityRepository()
    {
        return $this->getDoctrine()
            ->getEntityManager()
            ->getRepository($this->activityRepositoryName);
    }

    /**
     * @param string $viewTodayList e.g "AcmeBundle:SubFolder:myview.html.twig"
     */
    protected function setViewTodayList($viewTodayList)
    {
        $this->viewTodayList = $viewTodayList;
    }

    /**
     * @param array $optionsTodayList
     */
    protected function setOptionsTodayList($optionsTodayList)
    {
        $this->optionsTodayList = $optionsTodayList;
    }

    /**
     * @return mixed
     */
    protected function getOptionsTodayList()
    {
        return $this->optionsTodayList;
    }




    /*========================================================================
        attributes
    ========================================================================*/
    /**
     * @var \WCS\CantineBundle\Entity\ActivityRepositoryAbstract
     */
    private $activityRepository;
    private $activityRepositoryName;
    private $viewTodayList;
    private $optionsTodayList = array();
}
