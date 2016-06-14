<?php
//WCS/CantineBundle/Admin/LunchAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use WCS\CantineBundle\Entity\ActivityBase;

class LunchAdmin extends ActivityAdmin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('eleve', null, array(), array('admin_code'=>'sonata.admin.eleve'))
            ->add('date','sonata_type_date_picker',(array(
                'label'=>'Date',
                'format' => 'dd/MM/y'
            )))
            ->add('status', 'choice', array(
                'choices' => array(
                    null => 'Choisissez le statut',
                    ActivityBase::STATUS_REGISTERED_BUT_ABSENT  => 'Inscrit mais absent',
                    ActivityBase::STATUS_NOT_REGISTERED         => 'Non-Inscrit',
                    ActivityBase::STATUS_REGISTERED_AND_PRESENT => 'Inscrit et présent',
                ),
                'label' => false,
                'required' => false
            ))
        ;
    }
}
