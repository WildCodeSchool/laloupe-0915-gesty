<?php

namespace VoyageBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use VoyageBundle\Entity\Post;

/**
 * Post controller.
 *
 */
class PostController extends Controller
{

    /**
     * Lists all Post entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VoyageBundle:Post')->findAll();

        return $this->render('VoyageBundle:Post:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Post entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VoyageBundle:Post')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Post entity.');
        }

        return $this->render('VoyageBundle:Post:show.html.twig', array(
            'entity'      => $entity,
        ));
    }
}
