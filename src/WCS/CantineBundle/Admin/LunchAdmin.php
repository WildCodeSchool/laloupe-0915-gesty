<?php
//WCS/CantineBundle/Admin/LunchAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Symfony\Component\Validator\Constraints\DateTime;
use WCS\CantineBundle\Entity\LunchRepository;

class LunchAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('eleve', null)
            ->add('date','sonata_type_date_picker',(array(
                'label'=>'Date',
                'format' => 'dd-MM-y'
            )))
            ->add('status', 'choice', array(
                'choices' => array(
                    null => 'Choisissez le statut',
                    '0' => 'Inscrit mais absent',
                    '1' => 'Non-Inscrit',
                    '2' => 'Inscrit et présent',
                ),
                'label' => false
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('eleve', null)
            ->add('date', 'doctrine_orm_date_range', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ))
        ;

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('eleve', null)
            ->add('date', 'date', array(
                'format' => 'd M Y',
                'label' => false
            ))
            ->add('status', 'choice', array(
                'choices' => array(
                    null => 'Choisissez le statut',
                    '0' => 'Inscrit mais absent',
                    '1' => 'Non-Inscrit',
                    '2' => 'Inscrit et présent',
                ),
                'label' => false
            ))
            ->add('_action', 'actions', array('label' => 'Action', 'actions' => array(
                'edit' => array(),
                'delete' => array()
            )));

        ;
    }


}