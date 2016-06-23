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

    public function getContainer()
    {
        return $this->redirect($this->generateUrl('WCS/CantineBundle/Ressources/views/statmonthly.html.twig'));
    }

    public function count()
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->getQuery()
            ->getSingleScalarResult();
    }
    
    
}