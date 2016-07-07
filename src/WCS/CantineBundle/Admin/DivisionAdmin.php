<?php
//WCS/CantineBundle/Admin/DivisionAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class DivisionAdmin extends Admin
{


    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('grade','text',(array('label'=>'Classe')))
            ->add('headTeacher','text',(array('label'=>'Instituteur')))
            ->add('school',null,(array('label'=>'Ecole')))
            ;


    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('grade',null,(array('label'=>'Classe')))
            ->add('headTeacher',null,(array('label'=>'Instituteur')))
            ->add('school',null,(array('label'=>'Ecole')))
        ;

    }

    protected function configureShowFields(ShowMapper $datagridMapper)
    {
        $datagridMapper
            ->add('grade',null,(array('label'=>'Classe')))
            ->add('headTeacher',null,(array('label'=>'Instituteur')))
            ->add('school',null,(array('label'=>'Ecole')))
            ->add('eleves', null, array('admin_code'=>'sonata.admin.eleve'))
        ;

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('grade', 'text',(array('label'=>'Classe')))
            ->add('headTeacher', 'text',(array('label'=>'Instituteur')))
            ->add('school',null,(array('label'=>'Ecole')))
            ->add('_action', 'actions', array('actions' => array(
                'edit' => array(),
                'delete' => array(),
            )));
    }

}
