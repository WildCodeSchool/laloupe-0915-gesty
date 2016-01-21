<?php
//WCS/CantineBundle/Admin/FeriesAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class FeriesAdmin extends Admin
{


    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('annee', 'choice', array(
                'choices'   => array(\date('Y') => \date('Y'), \date('Y') + 1 => \date('Y') + 1,
                    \date('Y') + 2 => \date('Y') + 2, \date('Y') + 3 => \date('Y') + 3,
                    \date('Y') + 4 => \date('Y') + 4)))
            ->add('paques','sonata_type_date_picker',(array(
                'label'=>'Pâques',
                'format' => 'dd-MM-y'
            )))
            ->add('ascension','sonata_type_date_picker',(array(
                'label'=>'Ascension',
                'format' => 'dd-MM-y'
            )))
            ->add('pentecote','sonata_type_date_picker',(array(
                'label'=>'Pentecôte',
                'format' => 'dd-MM-y'
            )));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('annee','text',(array('label'=>'Année')))
            ->add('paques','date',(array(
                'label'=>'Pâques',
                'format' => 'd M Y'
            )))
            ->add('ascension','date',(array(
                'label'=>'Ascension',
                'format' => 'd M Y'
            )))
            ->add('pentecote','date',(array(
                'label'=>'Pentecôte',
                'format' => 'd M Y'
            )))
            ->add('_action', 'actions', array('label' => 'Action', 'actions' => array(
                'edit' => array(),
                'delete' => array()
            )));
    }
}
