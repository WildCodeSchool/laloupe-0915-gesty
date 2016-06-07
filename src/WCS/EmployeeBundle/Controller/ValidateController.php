<?php
namespace WCS\EmployeeBundle\Controller;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\ActivityBase;
use WCS\CantineBundle\Entity\School;
use WCS\EmployeeBundle\Form\Type\StatusType;

class ValidateController extends EmployeeController
{
    private $mapper;

    /**
     * ValidateController constructor.
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
        $entityClassName = $this->mapper->getEntityClassName();

        $entity = new $entityClassName;
        $form = $this->createForm(new StatusType(), $entity, array('data_class' => $entityClassName));

        $redirectTo = '';

        if ($this->processForm($request, $form)) {
            $redirectTo = $this->generateUrl(
                'wcs_employee_schools',
                array('activity'=>$activity)
            );
        }

        return array(
            'form_validate' => $form->createView(),
            'redirect_to'   => $redirectTo
        );
    }

    /**
     * @param Request $request
     * @param Form $form
     * @return bool
     */
    private function processForm(Request $request, Form $form)
    {
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            $em = $this->getDoctrine()->getManager();
            $repo = $em->getRepository($this->mapper->getEntityClassName());

            foreach (explode(',', $form["status"]->getData()) as $id)
            {
                if (!empty($id)) {
                    $entity = $repo->find($id);
                    if ($entity) {
                        $entity->setStatus(ActivityBase::STATUS_REGISTERED_AND_PRESENT);
                        $em->persist($entity);
                    }
                }
            }
            $em->flush();

            return true;
        }

        return false;
    }
}
