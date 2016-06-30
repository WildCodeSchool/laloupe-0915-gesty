<?php
namespace WCS\EmployeeBundle\Controller\ViewBuilder;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\ActivityBase;
use WCS\CantineBundle\Entity\School;
use WCS\EmployeeBundle\Controller\Mapper\ActivityMapperInterface;
use WCS\EmployeeBundle\Form\Type\ListType;


class ListViewBuilder extends ViewBuilderAbstract
{
    private $mapper;

    /**
     * RegisterController constructor.
     * @param ActivityMapperInterface $mapper
     * @param ContainerInterface $container
     */
    public function __construct(ActivityMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param Request $request
     * @param School $school
     * @param $activity
     * @return array
     */
    public function buildView(Request $request, School $school, $activity)
    {
        // prepare local variables

        $entityClass    = $this->mapper->getEntityClassName();
        $entity         = new $entityClass;
        $redirectTo     = '';


        // merge all options to transmit to the getActivityDayList repository's method

        $options = array_merge(
            $this->mapper->getDayListAdditionalOptions(),
            array(
                'school'    => $school,
                'date_day'  => $this->getDateDay()
            )
        );

        // create the form

        $form = $this->createForm(
            new ListType(),
            $entity,
            [ 'additional_options' => [
                'school_id' => $school->getId(),
                'date_day'  => $this->getDateDay(),
                'activity_type' => $this->mapper->getActivityType()
                ]
            ]
        );


        // process the form if any POST

        if ($this->processForm($request, $form, $entity)) {
            $redirectTo = $this->generateUrl(
                'wcs_employee_daylist',
                array('activity'=>$activity, 'id'=>$school->getId())
            );
        }

        // load the list (important : let this instruction AFTER creating the form in order to get
        // new registrations or removing from a previous post taken in account.

        $entities = $this->loadRegisteredPupilsList($options);

        // return the array with all data

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
    private function processForm(Request $request, Form $form, ActivityBase $entity)
    {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $key = $request->get('activity').'_list_eleves';
            $this->setSessionValue($key, $request->get("list_eleves"));

            $entity->setStatus(ActivityBase::STATUS_NOT_REGISTERED);
            $entity->setDate($this->getDateDay());

            $this->mapper->preUpdateEntity($entity);

            $em = $this->getDoctrineManager();
            $em->persist($entity);
            $em->flush();
            return true;
        }

        return false;
    }

    /**
     * @param $options
     * @return mixed
     */
    private function loadRegisteredPupilsList($options)
    {
        return $this->getRepository($this->mapper->getEntityClassName())->getActivityDayList($options);
    }
}
