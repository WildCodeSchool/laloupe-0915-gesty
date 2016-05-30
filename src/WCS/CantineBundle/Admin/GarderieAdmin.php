<?php
//WCS/CantineBundle/Admin/GarderieAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class GarderieAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('eleve', null)
            ->add('date_heure','sonata_type_datetime_picker',(array(
                'label'=>'Date',
                'format' => 'dd-MM-y HH:ii'
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
            ->add('eleve', null)
            ->add('date_heure', 'doctrine_orm_datetime_range', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:ii',
            ))
        ;

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('eleve', null)
            ->add('date_heure', 'date', array(
                'format' => 'd/m/Y H i',
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