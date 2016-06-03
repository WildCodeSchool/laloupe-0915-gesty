<?php
//WCS/CantineBundle/Admin/LunchAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LunchAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('eleve', null, array(), array('admin_code'=>'sonata.admin.eleve'))
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
                'label' => false,
                'required' => false
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('eleve', null, array('admin_code'=>'sonata.admin.eleve'))
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
            ->add('eleve', null, array('admin_code'=>'sonata.admin.eleve'))
            ->add('date', 'date', array(
                'format' => 'd/m/Y',
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
