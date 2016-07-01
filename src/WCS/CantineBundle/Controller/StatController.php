<?php
namespace WCS\CantineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use WCS\CantineBundle\Entity\User;
use WCS\CantineBundle\Entity\Eleve;

class StatController extends Controller
{

    public function countAction()
    {
        $eleves = $this->loadStatsFromRepository();

        return $this->render('WCSCantineBundle:Block:statmonthly.html.twig', array(
            'admin_pool' => $this->get('sonata.admin.pool'),
            'eleves'=>$eleves,
        ));

    }

    private function loadStatsFromRepository()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('WCSCantineBundle:Eleve');
        $eleves=array();
        $dateStart = new \DateTime('2016-06-15');
        $dateEnd = new \DateTime('2016-06-31');

        $liste_eleves = $repo->findAll();
        foreach ($liste_eleves as $eleve){

            $tmp['eleve'] = $eleve;
            $tmp['total_cantine'] = $repo->findTotalCantineFor(
                $eleve,
                $dateStart,
                $dateEnd
            );

            $tmp['total_tapgarderie'] = $repo->findTotalTapGarderieFor(
                $eleve,
                $dateStart,
                $dateEnd
            );
            $eleves[] = $tmp;
            
        }
        return $eleves;
    }

    
}