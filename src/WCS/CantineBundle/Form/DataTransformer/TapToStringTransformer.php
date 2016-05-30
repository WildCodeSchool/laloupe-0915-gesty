<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use WCS\CalendrierBundle\Service\Periode\Periode;
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

    public function __construct(ObjectManager $manager, Eleve $eleve, Periode $periode)
    {
        $this->manager = $manager;
        $this->eleve = $eleve;
        $this->daysOfWeek = new DaysOfWeeks($periode);
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

        $taps = array();
        $daysOfWeek = explode(';', $daysOfWeekString);
        $tap_all = $this->daysOfWeek->getListJoursTap();

        foreach ($daysOfWeek as $dayOfWeek)
        {
            foreach ($tap_all[$dayOfWeek] as $date) {
                $tap = new Tap();
                $tap->setEleve($this->eleve);
                $tap->setDate(new \DateTime($date));
                $this->manager->persist($tap);
                $taps[] = $tap;
            }
        }

        return $taps;
    }
}