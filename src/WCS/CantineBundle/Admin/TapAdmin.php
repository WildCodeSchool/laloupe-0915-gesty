<?php
//WCS/CantineBundle/Admin/TapAdmin.php
namespace WCS\CantineBundle\Admin;

use Doctrine\ORM\EntityRepository;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use WCS\CantineBundle\Entity\ActivityBase;
use WCS\CantineBundle\Entity\EleveRepository;


class TapAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper

            ->add('date','sonata_type_date_picker',(array(
                'label'=>'Date',
                'format' => 'dd/MM/y'
            )))
            ->add(
                'eleve',
                'entity',
                array(
                    'class'   => 'WCSCantineBundle:Eleve',

                    'query_builder' => function(EleveRepository $er)
                    {
                        return $er->getQueryElevesAutorisesEnTAP();
                    },
                    'required'  => false,
                    'mapped' => true
                ),
                array('admin_code'=>'sonata.admin.eleve')
            )
            ->add('status', 'choice', array(
                'choices' => array(
                    null => 'Choisissez le statut',
                    ActivityBase::STATUS_REGISTERED_BUT_ABSENT  => 'Inscrit mais absent',
                    ActivityBase::STATUS_NOT_REGISTERED         => 'Non-Inscrit',
                    ActivityBase::STATUS_REGISTERED_AND_PRESENT => 'Inscrit et présent'
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
                'format' => 'yyyy/MM/dd',
            ))
        ;

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('eleve', null, array('admin_code'=>'sonata.admin.eleve'))
            ->add('date', 'date', array(
                'format' => 'd/m/Y',
                'label' => false
            ))
            ->add('status', 'choice', array(
                'choices' => array(
                    null => 'Choisissez le statut',
                    ActivityBase::STATUS_REGISTERED_BUT_ABSENT  => 'Inscrit mais absent',
                    ActivityBase::STATUS_NOT_REGISTERED         => 'Non-Inscrit',
                    ActivityBase::STATUS_REGISTERED_AND_PRESENT => 'Inscrit et présent',
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
