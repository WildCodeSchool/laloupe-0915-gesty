<?php
//WCS/CantineBundle/Admin/SchoolAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class SchoolAdmin extends Admin
{


    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name',null,(array('label'=>'Nom de l\'école')))
            ->add('adress',null,(array('label'=>'Adresse')))
            ->add('phone',null,(array('label'=>'Téléphone')))
            ->add('active_tap',null,(array('label'=>'Gérer le TAP ?')))
            ->add('active_garderie',null,(array('label'=>'Gérer la garderie ?')))
            ->add('active_voyage',null,(array('label'=>'Gérer les voyages ?')))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name',null,(array('label'=>'Nom de l\'école')))
            ->add('adress',null,(array('label'=>'Adresse')))
            ->add('phone',null,(array('label'=>'Téléphone')))
        ;

    }

    // Fields to be shown on filter forms
    protected function configureShowFields(ShowMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name',null,(array('label'=>'Nom de l\'école')))
            ->add('adress',null,(array('label'=>'Adresse')))
            ->add('phone',null,(array('label'=>'Téléphone')))
            ->add('active_tap',null,(array('label'=>'TAP')))
            ->add('active_garderie',null,(array('label'=>'Garderie')))
            ->add('active_voyage',null,(array('label'=>'Voyage ?')))
            ->add('divisions')
        ;

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name',null,(array('label'=>'Nom de l\'école')))
            ->add('adress',null,(array('label'=>'Adresse')))
            ->add('phone',null,(array('label'=>'Téléphone')))
            ->add('active_tap',null,(array('label'=>'TAP')))
            ->add('active_garderie',null,(array('label'=>'Garderie')))
            ->add('active_voyage',null,(array('label'=>'Voyage ?')))
            ->add('_action', 'actions', array('actions' => array(
                'show' => array(),
                'edit' => array(),
                'delete' => array()
            )));
    }

}
