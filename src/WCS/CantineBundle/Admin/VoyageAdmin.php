<?php
//WCS/CantineBundle/Admin/DivisionAdmin.php
namespace WCS\CantineBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use WCS\CantineBundle\Entity\Division;


class VoyageAdmin extends Admin
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct($code, $class, $baseControllerName, \Doctrine\ORM\EntityManager $entityManager)
    {
        $this->em = $entityManager;
        parent::__construct($code, $class, $baseControllerName);
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $query = $this->em->getRepository('WCSCantineBundle:Voyage')->findByDivisions();

        $formMapper
            ->add('libelle', null)

            ->add('divisions', 'sonata_type_model', array(
                'query' => $query,
                'multiple' => true,
                'mapped' => true,
                'btn_add' => false
            ))

            ->add('date_debut','sonata_type_datetime_picker',(array(
                'label'=>'Date',
                'format' => 'dd/MM/y HH:mm'
            )))
            ->add('date_fin','sonata_type_datetime_picker',(array(
                'label'=>'Date',
                'format' => 'dd/MM/y HH:mm'
            )))

        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('libelle', null)
            //->add('eleves', null)
            ->add('divisions')
            ->add('date_debut', 'doctrine_orm_datetime_range', array(
                'widget' => 'single_text',
                'format' => 'YYYY-MM-DD HH:MM',
            ))
            ->add('date_fin', 'doctrine_orm_datetime_range', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:MM',
            ))
        ;

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('libelle', null)
            ->addIdentifier('id')
            //->add('eleves', null)
            ->add('divisions')



            ->add('date_debut', 'date', array(
                'format' => 'd/m/Y H:i',
                'label' => false
            ))
            ->add('date_fin', 'date', array(
                'format' => 'd/m/Y H:i',
                'label' => false
            ))
            ->add('estAnnule')
            ->add('_action', 'actions', array('label' => 'Action', 'actions' => array(
                'edit' => array(),
                'delete' => array()
            )));

        ;
    }


}