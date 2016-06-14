<?php
namespace WCS\CantineBundle\Request\ParamConverter;


use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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
     * EleveParamConverter constructor.
     * @param ManagerRegistry $managerRegistry
     * @param TokenStorageInterface $securityToken
     * @param Router $router
     */
    public function __construct(
        ManagerRegistry $managerRegistry,
        TokenStorageInterface $securityToken,
        Router $router
    )
    {
        $this->managerRegistry = $managerRegistry;
        $this->router = $router;
        $this->security_token_storage = $securityToken;
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
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private function getDoctrine()
    {
        if (!count($this->managerRegistry->getManagers())) {
            throw new \LogicException("Doctrine : no managers found");
        }
        return $this->managerRegistry;
    }

    /**
     * @return \Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    private function getToken()
    {
        return $this->security_token_storage->getToken();
    }


    /**
     * @param string $route
     */
    private function redirectTo($route)
    {
        $url = $this->router->generate($route);

        throw new HttpException('302', null, null, array('Location'=>$url), 0);
    }

    private $managerRegistry;
    private $router;
    private $security_token_storage;
}
