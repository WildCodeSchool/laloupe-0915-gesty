<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Tap;

class TapToStringTransformer implements DataTransformerInterface
{

    private $manager;
    private $eleve;

    /**
     * @var \WCS\CantineBundle\Form\DataTransformer\DaysOfWeeks
     */
    private $daysOfWeek;

    public function __construct(ObjectManager $manager, Eleve $eleve, DaysOfWeeks $daysOfWeek)
    {
        $this->manager = $manager;
        $this->eleve = $eleve;
        $this->daysOfWeek = $daysOfWeek;
    }

    /**
     * @param  Tap[] $taps
     * @return string indice des jours de la semaine séparé par des ";"
     */
    public function transform($taps)
    {
        if (null === $taps) {
            return '';
        }

        return implode(";", $this->daysOfWeek->getTapSelectionToArray($taps));
    }

    /**
     * @param  string $daysOfWeekString indice des jours de la semaine séparé par des ";"
     * @return Tap[]|null
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
