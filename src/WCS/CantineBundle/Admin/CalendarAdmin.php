<?php
//WCS/CantineBundle/Admin/CalendarAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class CalendarAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('period',null,array('label'=>'Année scolaire (ex: 2015-2016)'))
            ->add('start', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de début d\'année',))
            ->add('end', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de fin d\'année',))
            ->add('vacancesToussaintStart', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de fin d\'année',))
            ->add('vacancesToussaintEnd', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de fin d\'année',))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {


    }


}