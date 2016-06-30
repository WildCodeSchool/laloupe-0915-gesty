<?php
namespace WCS\CantineBundle\Admin;

use Doctrine\ORM\EntityManager;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use WCS\CantineBundle\Entity\SchoolHoliday;
use WCS\CantineBundle\Entity\SchoolYear;


class SchoolYearAdmin extends Admin
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $absoluteUploadPath;

    protected $datagridValues = array(
        '_sort_by' => 'dateStart',
    );


    /**
     * SchoolYearAdmin constructor.
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param string $absoluteUploadPath
     */
    public function __construct(
        $code,
        $class,
        $baseControllerName,
        \Doctrine\ORM\EntityManagerInterface $entityManager,
        $absoluteUploadPath
    )
    {
        $this->entityManager = $entityManager;
        $this->absoluteUploadPath = $absoluteUploadPath;
        parent::__construct($code, $class, $baseControllerName);
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('dateStart','sonata_type_date_picker',(array(
                'label'=>'Date de la rentrée scolaire',
                'format' => 'dd/MM/y'
            )))

            ->add('dateEnd','sonata_type_date_picker',(array(
                'label'=>"Date de fin de l'année scolaire",
                'format' => 'dd/MM/y'
            )))
            ->add('file', 'file', array('required'=>false, 'label'=> 'Fichier iCalendar'))

        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('dateStart', 'date', array(
                'format' => 'd/m/Y',
                'label' => 'Rentrée scolaire'
            ))
            ->add('dateEnd', 'date', array(
                'format' => 'd/m/Y',
                'label' => "Fin année scolaire"
            ))
            ->add('schoolHolidays', null, array('label' => 'Vacances scolaires', 'template' => 'ApplicationSonataUserBundle:CRUD:list_orm_many_to_many.html.twig'))
            ->add('_action', 'actions', array('label' => 'Action', 'actions' => array(
                'edit' => array(),
                'delete' => array()
            )));

        ;
    }

    public function toString($object)
    {
        $year = "";
        if ($object && $object->getDateStart() && $object->getDateEnd()) {
            $year = " ".$object->getDateStart()->format('Y');
            $year .= " / ";
            $year .= $object->getDateEnd()->format('Y');
        }
        return "Année scolaire".$year;
    }

    public function prePersist($object)
    {
        $this->manageFileUpload($object);
    }

    public function preUpdate($object)
    {
        $this->manageFileUpload($object);
    }

    public function postPersist($object)
    {
        $this->entityManager->getRepository('WCSCantineBundle:SchoolHoliday')->updateAllFrom($object);
    }

    public function postUpdate($object)
    {
        $this->postPersist($object);
    }

    /**
     * @param SchoolYear $object
     */
    private function manageFileUpload($object)
    {
        $object->setUploadAbsolutePath($this->absoluteUploadPath);
        
        if ($object->getFile()) {
            $object->refreshUpdated();
        }
    }

}
