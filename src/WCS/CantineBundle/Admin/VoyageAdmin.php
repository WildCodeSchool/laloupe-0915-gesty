<?php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use WCS\CantineBundle\Entity\DivisionRepository;


class VoyageAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('libelle', null, array('required'=>false))

            ->add('divisions',
                'entity',
                array(
                    'class'   => 'WCSCantineBundle:Division',

                    'query_builder' => function(DivisionRepository $er) {
                        return $er->getQueryVoyagesAutorises();
                    },
                    'multiple' => true,
                    'required'  => false,
                    'mapped' => true
                )
            )

            ->add('estAnnule', null, array('label'=>"Voyage/sortie annulée ?"))

            ->add('estSortieScolaire', null, array('label'=>"Cocher s'il s'agit uniquement d'une sortie scolaire ?"))


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
            ->add('divisions')


            ->add('estSortieScolaire', null, array('label' => 'Sortie scolaire ?'))

            ->add('date_debut', 'date', array(
                'format' => 'd/m/Y H:i',
                'label' => false
            ))
            ->add('date_fin', 'date', array(
                'format' => 'd/m/Y H:i',
                'label' => false
            ))
            ->add('estAnnule', null, array('label' => 'Annulé ?'))
            ->add('_action', 'actions', array('label' => 'Action', 'actions' => array(
                'edit' => array(),
                'delete' => array()
            )));

        ;
    }


}
