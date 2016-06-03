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
class GarderieMatinController extends GarderieController
{
    /**
     * CanteenController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->setOptionsTodayList(array('is_morning' => true));
        $this->form_action = 'wcs_employee_todaylist_garderie_matin';
        $this->type_activity = 'garderie_matin';
    }
}
