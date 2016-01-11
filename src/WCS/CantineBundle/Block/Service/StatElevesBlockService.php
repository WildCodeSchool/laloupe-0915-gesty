<?php

// src/WCS/CantineBundle/Block
namespace WCS\CantineBundle\Block\Service;


use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class StatElevesBlockService extends BaseBlockService
{
    /**
    * @var SecurityContextInterface
    */
    protected $securityContext;

    /**
     * @var EntityManager
     */
    protected $em;


    public function __construct($name, EngineInterface $templating, Pool $pool, EntityManager $em, SecurityContext $securityContext)
    {
        parent::__construct($name, $templating);

        $this->pool = $pool;
        $this->em = $em;
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Statistiques';
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        //eleves stats

       $this->em
            ->getRepository('WCSCantineBundle:Eleve')
            ->findAll();

        $currentWeekMeals = $this->em->getRepository('WCSCantineBundle:Eleve')->getCurrentWeekMeals();

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block'     => $blockContext->getBlock(),
            'base_template' => $this->pool->getTemplate('WCSCantineBundle:Block:stateleves.html.twig'),
            'settings'  => $blockContext->getSettings(),
            'currentWeekMeals'    => $currentWeekMeals

        ), $response);
    }
    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'title'    => 'Mes informations',
            'template' => 'WCSCantineBundle:Block:stateleves.html.twig' // Le template à render dans execute()
        ));
    }
    public function getDefaultSettings()
    {
        return array();
    }
    /**
     * {@inheritdoc}
     */
    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }
    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
    }
}

