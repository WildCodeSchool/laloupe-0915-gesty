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
                'label'=>'Date de début des vacances de la Toussaint',))
            ->add('vacancesToussaintEnd', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de fin des vacances de la Toussaint',))
            ->add('vacancesNoelStart', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de début des vacances de Noël',))
            ->add('vacancesNoelEnd', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de fin des vacances de Noël',))
            ->add('vacancesHiverStart', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>"Date de début des vacances d'hiver",))
            ->add('vacancesHiverEnd', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>"Date de fin des vacances d'hiver",))
            ->add('vacancesPrintempsStart', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de début des vacances de printemps',))
            ->add('vacancesPrintempsEnd', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Date de fin des vacances de printemps',))
            ->add('vacancesEte', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>"Date des vacances d'été",))
            ->add('feriePaques', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Lundi de Pâques (férié)',))
            ->add('feriePentecote', 'sonata_type_date_picker', array(
                'format' => 'y-MM-dd',
                'dp_use_current'        => false,
                'label'=>'Lundi de la Pentecôte (férié)',))
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