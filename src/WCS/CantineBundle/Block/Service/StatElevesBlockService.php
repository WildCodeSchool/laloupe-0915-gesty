<?php

namespace WCS\CantineBundle\Block\Service;


use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Admin\Pool;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Model\BlockInterface;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

use Doctrine\ORM\EntityManager;

use Scheduler\Component\DateContainer\DateNow;


class StatElevesBlockService extends BaseBlockService
{
    /**
     * @var Pool
     */
    private $pool;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $securityContext;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var DateNow
     */
    private $date_now_service;


    /**
     * StatElevesBlockService constructor.
     * @param string $name
     * @param EngineInterface $templating
     * @param Pool $pool
     * @param EntityManager $em
     * @param SecurityContext $securityContext
     * @param DateNow $dateNow
     */
    public function __construct(
        $name,
        EngineInterface $templating,
        Pool $pool,
        EntityManager $em,
        SecurityContext $securityContext,
        DateNow $dateNow
    )
    {
        parent::__construct($name, $templating);

        $this->pool             = $pool;
        $this->em               = $em;
        $this->securityContext  = $securityContext;
        $this->date_now_service = $dateNow;
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
        // Totals stats
        $tots = array(
            'users'     => $this->em->getRepository('ApplicationSonataUserBundle:User')->count(),
            'children'  => $this->em->getRepository('WCSCantineBundle:Eleve')->count(),
            'meals'     => $this->em->getRepository('WCSCantineBundle:Lunch')->count(),
            'schools'   => $this->em->getRepository('WCSCantineBundle:School')->count()
        );

        $options['date_day'] = $this->date_now_service->getDate();

        $repoLunch = $this->em->getRepository('WCSCantineBundle:Lunch');

        // stats lunch : current week
        $options['enable_next_week']    = false;

        $options['without_pork']        = true;
        $currentWeekMealsNoPork         = $repoLunch->getWeekMeals( $options );
        $options['without_pork']        = false;
        $currentWeekMeals               = $repoLunch->getWeekMeals( $options );

        // stats lunch : next week
        $options['enable_next_week']    = true;

        $options['without_pork']        = true;
        $nextWeekMealsNoPork            = $repoLunch->getWeekMeals( $options );
        $options['without_pork']        = false;
        $nextWeekMeals                  = $repoLunch->getWeekMeals( $options );

        return $this->renderResponse($blockContext->getTemplate(), array(
            'block'                     => $blockContext->getBlock(),
            'base_template'             => $this->pool->getTemplate('WCSCantineBundle:Block:stateleves.html.twig'),
            'settings'                  => $blockContext->getSettings(),
            'currentWeekMeals'          => $currentWeekMeals,
            'currentWeekMealsNoPork'    => $currentWeekMealsNoPork,
            'nextWeekMeals'             => $nextWeekMeals,
            'nextWeekMealsNoPork'       => $nextWeekMealsNoPork,
            'tots'                      => $tots

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

    /**
     * @return array
     */
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

