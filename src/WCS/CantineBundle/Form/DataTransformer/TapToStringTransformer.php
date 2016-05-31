<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Tap;
use WCS\CantineBundle\Service\FeriesDayList;

class TapToStringTransformer implements DataTransformerInterface
{

    private $manager;
    private $eleve;

    /**
     * @var \WCS\CantineBundle\Form\DataTransformer\DaysOfWeeks
     */
    private $daysOfWeek;

    public function __construct(ObjectManager $manager, Eleve $eleve, Periode $periode, FeriesDayList $feriesDayList)
    {
        $this->manager = $manager;
        $this->eleve = $eleve;
        $this->daysOfWeek = new DaysOfWeeks($periode, $feriesDayList);
    }

    /**
     * transforme recoit une liste de "tap" de l'élève
     * récupère la date de chacun, puis renvoit une chaine
     * contenant uniquement les dates, formattées (Y-m-d) et séparées par un ";"
     *
     * @param  array $taps
     * @return string
     */
    public function transform($taps)
    {
        if (null === $taps) {
            return '';
        }

        return implode(";", $this->daysOfWeek->getTapSelectionToArray($taps));
    }

    /**
     * Récupère une chaine de dates formattées (Y-m-d) séparées par un ";"
     * et renvoit une liste de "tap" pour l'élève pour chacune des dates.
     *
     * @param  string $daysOfWeekString indice des jours de la semaine ";"
     * @return array de Taps renvoit une liste d'entité "Tap" pour cet élève ou une liste vide
     */
    public function reverseTransform($daysOfWeekString)
    {
        if (empty($daysOfWeekString)) {
            return array();
        }

        $daysOfWeek = explode(';', $daysOfWeekString);

        $taps_periode   = $this->daysOfWeek->getListJoursTap();
        $taps_eleve     = $this->manager->getRepository('WCSCantineBundle:Eleve')->findAllTapsForPeriode(
            $this->eleve,
            $this->daysOfWeek->getPeriode());
        $taps = array();
        foreach ($daysOfWeek as $dayOfWeek)
        {
            foreach ($taps_periode[$dayOfWeek] as $date) {

                // si la réservation est déjà présente,
                // on se contente de l'ajouter dans la liste
                // sinon on créé une nouvelle réservation
                $dateT = new \DateTime($date);
                $found = false;

                foreach($taps_eleve as $current) {
                    if ($current->getDate()==$dateT) {
                        $tap = $current;
                        $found = true;
                    }
                }

                if (!$found) {
                    $tap = new Tap();
                    $tap->setEleve($this->eleve);
                    $tap->setDate($dateT);

                    $this->manager->persist($tap);
                }
                $taps[] = $tap;
            }
        }

        return $taps;
    }
}
