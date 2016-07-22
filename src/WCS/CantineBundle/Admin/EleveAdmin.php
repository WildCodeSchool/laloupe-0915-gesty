<?php
//WCS/CantineBundle/Admin/EleveAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Scheduler\Component\DateContainer\DateNow;


class EleveAdmin extends Admin
{
    /**
     * @var DateNow
     */
    private $date_now_service;

    public function __construct($code, $class, $baseControllerName, DateNow $date_now_service)
    {
        $this->date_now_service = $date_now_service;
        parent::__construct($code, $class, $baseControllerName);
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $year = $this->date_now_service->getDate()->format('Y');

        $formMapper
            ->add('user',null,array('label'=>'Email des parents'))
            ->add('nom','text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance', 'date', array(
                'format' => 'dd-MM-yyyy',
                'years' =>  range($year - 11, $year - 2),))
            ->add('regimeSansPorc', null, array('required' => false))
            ->add('allergie',null ,array('required' => false))
            ->add('division', 'entity', array(
                'class' => 'WCSCantineBundle:Division',
                'label' => 'Classe',
                'required'=>true ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nom')
            ->add('prenom')
            ->add('dateDeNaissance')
            ->add('division',null, array('label'=>'Classe'))


        ;
    }

    protected function configureShowFields(ShowMapper $datagridMapper)
    {
        $year = $this->date_now_service->getDate()->format('Y');

        $datagridMapper
            ->add('user',null,array('label'=>'Email des parents'))
            ->add('nom','text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance', 'date', array(
                'format' => 'd-M-Y',
                'years' =>  range($year - 11, $year - 2),))
            ->add('regimeSansPorc', null, array('required' => false))
            ->add('allergie',null ,array('required' => false))
            ->add('division', 'entity', array(
                'class' => 'WCSCantineBundle:Division',
                'required'=>true,
                'label' => 'classe'))
        ;

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('nom', 'text')
            ->add('prenom', 'text')
            ->add('dateDeNaissance', 'date', array('format' => 'd/m/Y',))
            ->add('division','choice', array('label'=>'Classe'))
            ->add('user',null, array('label'=>'Email des parents'))
            ->add('allergie', 'text')
            ->add('regimeSansPorc', 'boolean')
            ->add('_action', 'actions',
                array('actions' => array(

                    'edit' => array(),
                    'delete' => array(),
            )))

        ;
    }

}
