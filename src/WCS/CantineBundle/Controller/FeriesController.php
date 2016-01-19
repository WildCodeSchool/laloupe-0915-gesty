<?php

namespace WCS\CantineBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use WCS\CantineBundle\Entity\Feries;
use WCS\CantineBundle\Form\Type\FeriesType;

/**
 * Feries controller.
 *
 */
class FeriesController extends Controller
{

    /**
     * Lists all Feries entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('WCSCantineBundle:Feries')->findAll();

        return $this->render('WCSCantineBundle:Feries:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Feries entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Feries();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('feries_show', array('id' => $entity->getId())));
        }

        return $this->render('WCSCantineBundle:Feries:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Feries entity.
     *
     * @param Feries $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Feries $entity)
    {
        $form = $this->createForm(new FeriesType(), $entity, array(
            'action' => $this->generateUrl('feries_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Feries entity.
     *
     */
    public function newAction()
    {
        $entity = new Feries();
        $form   = $this->createCreateForm($entity);

        return $this->render('WCSCantineBundle:Feries:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Feries entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WCSCantineBundle:Feries')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feries entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('WCSCantineBundle:Feries:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Feries entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WCSCantineBundle:Feries')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feries entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('WCSCantineBundle:Feries:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Feries entity.
    *
    * @param Feries $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Feries $entity)
    {
        $form = $this->createForm(new FeriesType(), $entity, array(
            'action' => $this->generateUrl('feries_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Feries entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('WCSCantineBundle:Feries')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Feries entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('feries_edit', array('id' => $id)));
        }

        return $this->render('WCSCantineBundle:Feries:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Feries entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('WCSCantineBundle:Feries')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Feries entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('feries'));
    }

    /**
     * Creates a form to delete a Feries entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('feries_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
