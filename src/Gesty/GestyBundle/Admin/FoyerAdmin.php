<?php

//Gesty/GestyBundle/Admin/FoyerAdmin.php
namespace Gesty\GestyBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class FoyerAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'ordre'
    );

    protected $maxPerPage = 5;

//Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('titre', 'text', array('label' => 'Titre'))
            ->add('auteur')
            ->add('contenu') //if no type is specified, SonataAdminBundle tries to guess it
#->add('categories')
            ->add('categories', 'sonata_type_model',array('expanded' => true, 'compound' => true, 'multiple' => true))
            ->setHelps(array(
                'titre' => $this->trans('help_post_title')
            ))

        ;
    }

//Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('titre')
            ->add('slug')
            ->add('auteur')
        ;
    }

//Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('titre')
            ->add('slug')
            ->add('auteur')
            ->add('categories')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'view' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }
}