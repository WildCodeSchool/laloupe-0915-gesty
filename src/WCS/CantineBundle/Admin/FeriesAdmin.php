<?php
//WCS/CantineBundle/Admin/FeriesAdmin.php
namespace WCS\CantineBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class FeriesAdmin extends Admin
{
    /**
     * @var \WCS\CalendrierBundle\Service\DateNow
     */
    private $date_day_service;

    /**
     * FeriesAdmin constructor.
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param \WCS\CalendrierBundle\Service\DateNow $date_day_service
     */
    public function __construct($code, $class, $baseControllerName, \WCS\CalendrierBundle\Service\DateNow $date_day_service)
    {
        $this->date_day_service = $date_day_service;
        parent::__construct($code, $class, $baseControllerName);
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $year = $this->date_day_service->getDate()->format('Y');
        $years = range(2015, \intval($year) + 10);
        $years = array_combine($years, $years);
        $formMapper
            ->add('annee', 'choice', array(
                /*
                'choices'   => array(
                    \date('Y') => \date('Y'),
                    \date('Y') + 1 => \date('Y') + 1,
                    \date('Y') + 2 => \date('Y') + 2,
                    \date('Y') + 3 => \date('Y') + 3,
                    \date('Y') + 4 => \date('Y') + 4
                )*/

                'choices' => $years
                ))
            ->add('paques','sonata_type_date_picker',(array(
                'label'=>'Lundi Pâques',
                'format' => 'dd-MM-y'
            )))
            ->add('ascension','sonata_type_date_picker',(array(
                'label'=>'Ascension',
                'format' => 'dd-MM-y'
            )))
            ->add('vendredi_ascension','sonata_type_date_picker',(array(
                'label'=>'Vendredi Ascension',
                'format' => 'dd-MM-y'
            )))
            ->add('pentecote','sonata_type_date_picker',(array(
                'label'=>'Lundi Pentecôte',
                'format' => 'dd-MM-y'
            )));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {

    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('annee','text',(array('label'=>'Année')))
            ->add('paques','date',(array(
                'label'=>'Lun Pâques',
                'format' => 'd M Y'
            )))
            ->add('ascension','date',(array(
                'label'=>'Jeu Ascension',
                'format' => 'd M Y'
            )))
            ->add('vendredi_ascension','date',(array(
                'label'=>'Ven Ascension',
                'format' => 'd M Y'
            )))
            ->add('pentecote','date',(array(
                'label'=>'Lun Pentecôte',
                'format' => 'd M Y'
            )))
            ->add('_action', 'actions', array('label' => 'Action', 'actions' => array(
                'edit' => array(),
                'delete' => array()
            )));
    }
}
