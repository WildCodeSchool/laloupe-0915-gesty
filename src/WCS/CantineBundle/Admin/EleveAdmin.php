<?php
//WCS/CantineBundle/Admin/EleveAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class EleveAdmin extends Admin
{




    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('user')
            ->add('nom','text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance','date')
            ->add('regimeSansPorc', null, array('required' => false))
            ->add('allergie',null ,array('required' => false))
            ->add('Etablissement', 'entity', array(
                'class' => 'WCSCantineBundle:Etablissement'))
            ->add('atteste')
            ->add('autorise')
            ->add('certifie')
            ->add('dates')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nom')
            ->add('prenom')
            ->add('dateDeNaissance')


        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('nom', 'text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance', 'date', array('format' => 'd/m/Y',))
            ->add('Etablissement','choice', array('label'=>'classe'))
            ->add('_action', 'actions', array('actions' => array(
                'edit' => array(),
                'delete' => array(),
            )))
            ->add('allergie', 'text')
            ->add('regimeSansPorc', 'boolean')
        ;
    }


}