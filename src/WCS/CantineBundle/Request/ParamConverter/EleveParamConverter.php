<?php
namespace WCS\CantineBundle\Request\ParamConverter;


use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class EleveParamConverter
 *
 * Convert Request routes with controller expecting the pupil entity as argument.
 * This class add a security layer by ensuring the "Eleve" asked from the route belongs to the connected parent.
 *
 */
class EleveParamConverter implements ParamConverterInterface
{
    const REDIRECT_TO_ROUTE_FORBIDDENACCESS = 'fos_user_security_logout';

    /**
     * @param ContainerInterface $container Service container
     */
    public function __construct( ContainerInterface $container )
    {
        $this->container = $container;
    }


    /**
     * @inheritdoc
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        // get the id of the pupil
        $id = $request->attributes->get('id');
        if (null === $id) {
            return false;
        }

        // get the manager for the given class
        $manager = $this->getDoctrine()->getManagerForClass(
            $configuration->getClass()
        );
        if (null === $manager) {
            return false;
        }

        // find the entity
        $entity = $manager->getRepository('WCSCantineBundle:Eleve')->findByIdAndParent(
            $id,
            $this->getToken()->getUser()
        );
        if (null === $entity) {
            $this->redirectTo(self::REDIRECT_TO_ROUTE_FORBIDDENACCESS);
        }

        // pass the entity to the request (and ultimately to the controller)
        $request->attributes->set($configuration->getName(), $entity);

        return true;
    }



    /**
     * @inheritdoc
     */
    public function supports(ParamConverter $configuration)
    {
        // Check, if option class was set in configuration
        if (null === $configuration->getClass()) {
            return false;
        }

        // Get the manager for the passed class (by default, the default entity manager)
        $manager = $this->getDoctrine()->getManagerForClass($configuration->getClass());

        // Check that the manager has the correct Class
        if ('WCS\CantineBundle\Entity\Eleve' !== $manager->getClassMetadata($configuration->getClass())->getName()) {
            return false;
        }

        return true;
    }




    /**
     * @param string $name of the service
     * @return object
     * @throws \LogicException if the service is not available.
     */
    private function getService($name)
    {
        $service = $this->container->get($name);
        if (!$service) {
            throw new \LogicException("$name service unavailable");
        }
        return $service;
    }

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private function getDoctrine()
    {
        $doctrine =  $this->getService('doctrine');
        if (!count($doctrine->getManagers())) {
            throw new \LogicException("Doctrine : no managers found");
        }
        return $doctrine;
    }

    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    private function getToken()
    {
        return $this->getService('security.token_storage')->getToken();
    }


    /**
     * @param string $route
     */
    private function redirectTo($route)
    {
        $url = $this->getService('router')->generate($route);

        throw new HttpException('302', null, null, array('Location'=>$url), 0);
    }

    /**
     * @var ContainerInterface
     */
    private $container;

}
