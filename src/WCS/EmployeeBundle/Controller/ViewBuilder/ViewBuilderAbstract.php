<?php
namespace WCS\EmployeeBundle\Controller\ViewBuilder;


use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use WCS\CantineBundle\Entity\School;

abstract class ViewBuilderAbstract extends ContainerAware
{
    /**
     * @param Request $request
     * @param School $school
     * @param string $activity
     * @return mixed
     */
    abstract public function buildView(Request $request, School $school, $activity);


    /**
     * return the repository of the given entity
     *
     * @param string $entityClassName The entity path
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($entityClassName)
    {
        return $this->getDoctrineManager()->getRepository($entityClassName);
    }

    /**
     * Same as Controller::createForm
     *
     * @param $type
     * @param null $data
     * @param array $options
     * @return \Symfony\Component\Form\Form|\Symfony\Component\Form\FormInterface
     */
    protected function createForm($type, $data = null, array $options = array())
    {
        return $this->container->get('form.factory')->create($type, $data, $options);
    }

    /**
     * Set a session custom parameter
     * 
     * @param $key
     * @param $value
     */
    protected function setSessionValue($key, $value)
    {
        $this->container->get('session')->set($key, $value);
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object the doctrine manager
     */
    protected function getDoctrineManager()
    {
        return $this->container->get('doctrine')->getManager();
    }

    /**
     * Same as Controller::generateUrl
     *
     * @param $route
     * @param array $parameters
     * @return string
     */
    protected function generateUrl($route, $parameters = array())
    {
        return $this->container->get('router')->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * @return \DateTimeImmutable the date of the current day.
     */
    public function getDateDay()
    {
        return $this->container->get('wcs.datenow')->getDate();
    }

}
