<?php
namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Tap;
use WCS\CantineBundle\Entity\Lunch;
use WCS\CantineBundle\Entity\Garderie;
use WCS\CalendrierBundle\Service\Periode\Periode;

class StatController extends Controller
{

    public function countAction()
    {
        return $this->render('WCSCantineBundle:Block:statmonthly.html.twig');
    }

    /*public function countAction()
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->getQuery()
            ->getSingleScalarResult();
    }
*/
    
}