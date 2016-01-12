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
            ->add('user',null,array('label'=>'Email des parents'))
            ->add('nom','text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance', 'date', array(
                'format' => 'dd-MM-yyyy',
                'years' =>  range(\date("Y") - 11, \date("Y") - 2),))
            ->add('regimeSansPorc', null, array('required' => false))
            ->add('allergie',null ,array('required' => false))
            ->add('division', 'entity', array(
                'class' => 'WCSCantineBundle:Division'))
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
            ->add('dates','doctrine_orm_date_range',
                    ['field_type' => 'sonata_type_date_range' ,
                    'field_options'=> array('widget' => 'single_text', 'required' => false, 'attr' => array('class' => 'datepicker'))])
            ->add('division',null, array('label'=>'Classe'))


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
            ->add('division','choice', array('label'=>'Classe'))
            ->add('user',null, array('label'=>'Email des parents'))
            ->add('_action', 'actions', array('actions' => array(
                'edit' => array(),
                'delete' => array(),
            )))
            ->add('allergie', 'text')
            ->add('regimeSansPorc', 'boolean')
            ->add('dates')
        ;
    }


}