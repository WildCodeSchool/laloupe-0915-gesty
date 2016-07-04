<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Garderie;
use WCS\CantineBundle\Service\GestyScheduler\DaysOfWeeks;

class GarderieToStringTransformer implements DataTransformerInterface
{

    private $manager;
    private $eleve;

    /**
     * @var DaysOfWeeks
     */
    private $daysOfWeek;

    public function __construct(ObjectManager $manager, Eleve $eleve, DaysOfWeeks $daysOfWeek)
    {
        $this->manager = $manager;
        $this->eleve = $eleve;
        $this->daysOfWeek = $daysOfWeek;
    }

    /**
     * @param  Garderie[] $garderies
     * @return string indice des jours de la semaine séparé par des ";"
     */
    public function transform($garderies)
    {
        if (null === $garderies) {
            return '';
        }

        return implode(";", $this->daysOfWeek->getGarderieSelectionToArray($garderies));
    }

    /**
     * @param  string $daysOfWeekString indice des jours de la semaine séparé par des ";"
     * @return Garderie[]|null
     */
    public function reverseTransform($daysOfWeekString)
    {
        if (empty($daysOfWeekString)) {
            return array();
        }

        $daysOfWeek = explode(';', $daysOfWeekString);

        $garderies_periode   = $this->daysOfWeek->getListJoursGarderie();
        $garderies_eleve     = $this->manager->getRepository('WCSCantineBundle:Eleve')->findAllGarderiesForPeriode(
            $this->eleve,
            $this->daysOfWeek->getPeriode());

        $garderies = array();
        foreach ($daysOfWeek as $dayOfWeek)
        {
            foreach ($garderies_periode[$dayOfWeek] as $date) {

                // si la réservation est déjà présente,
                // on se contente de l'ajouter dans la liste
                // sinon on créé une nouvelle réservation
                $dateT = new \DateTime($date);
                $found = false;
                $garderie = null;
                
                foreach($garderies_eleve as $current) {
                    if ($current->getDate()==$dateT) {
                        if (substr($dayOfWeek, -2)=='-1' && $current->isEnableMorning()) {
                            $garderie = $current;
                            $found = true;
                        }
                        if (substr($dayOfWeek, -2)=='-2' && $current->isEnableEvening()) {
                            $garderie = $current;
                            $found = true;
                        }

                    }
                }

                if (!$found) {
                    $garderie = new Garderie();
                    $garderie->setEleve($this->eleve);
                    $garderie->setDate($dateT);
                    if (substr($dayOfWeek, -2)=='-1') {
                        $garderie->setEnableMorning(true);
                    }
                    if (substr($dayOfWeek, -2)=='-2') {
                        $garderie->setEnableEvening(true);
                    }

                    $this->manager->persist($garderie);
                }
                $garderies[] = $garderie;
            }
        }


        return $garderies;
    }
}

