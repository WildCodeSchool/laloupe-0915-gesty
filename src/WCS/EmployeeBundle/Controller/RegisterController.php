<?php
namespace WCS\EmployeeBundle\Controller;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\School;
use WCS\EmployeeBundle\Form\Type\ActivityEleveType;


class RegisterController extends EmployeeController
{
    private $mapper;

    /**
     * RegisterController constructor.
     * @param Mapper\ActivityMapperInterface $mapper
     */
    public function __construct(Mapper\ActivityMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param Request $request
     * @param School $school
     * @param $activity
     * @return array
     */
    public function showAction(Request $request, School $school, $activity)
    {
        $options = array_merge(
            $this->mapper->getDayListAdditionalOptions(),
            array(
                'school'    => $school,
                'date_day'  => $this->getDateDay()
            )
        );

        $entities = $this->getRepository($this->mapper->getEntityClassName())->getActivityDayList($options);

        $entityClass = $this->mapper->getEntityClassName();
        $entity = new $entityClass;
        $form = $this->createForm(
            new ActivityEleveType($entityClass),
            $entity,
            [ 'additional_options' => [ 'school_id' => $school->getId() ] ]
        );

        $redirectTo = '';
        
        if ($this->processForm($request, $form, $entity)) {
            $redirectTo = $this->generateUrl(
                'wcs_employee_daylist',
                array('activity'=>$activity, 'id'=>$school->getId())
            );
        }

        return array(
            'entities'      => $entities,
            'form_register' => $form->createView(),
            'redirect_to'    => $redirectTo
        );
    }

    /**
     * @param Request $request
     * @param Form $form
     * @param $entity
     * @return bool
     */
    private function processForm(Request $request, Form $form, $entity)
    {
        $form->handleRequest($request);

        if ($form->isValid()) {

            $this->mapper->updateEntity(
                $entity, $this->getDateDay()
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return true;
        }

        return false;
    }
}
