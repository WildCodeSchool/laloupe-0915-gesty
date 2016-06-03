<?php
namespace WCS\EmployeeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use WCS\EmployeeBundle\Form\Type\GarderiePresentType;
use WCS\CantineBundle\Entity\School;
use WCS\CantineBundle\Entity\Garderie;


/**
 * List controller.
 *
 */
class GarderieSoirController extends GarderieController
{
    /**
     * CanteenController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setOptionsTodayList(array('is_morning' => false));
        $this->form_action = 'wcs_employee_todaylist_garderie_soir';
        $this->type_activity = 'garderie_soir';
    }
}
