<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use WCS\CalendrierBundle\Service\Periode\Periode;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Garderie;



class GarderieToStringTransformer implements DataTransformerInterface
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
    public function transform($garderies)
    {
        if (null === $garderies) {
            return '';
        }

        $garderies_all = $this->daysOfWeek->getListJoursGarderie();
        $tmp = array();
        foreach($garderies as $garderie) {
            foreach ($garderies_all as $dayOfWeek => $datesheures) {

                $dateheure  = $garderie->getDateHeure()->format('Y-m-d H:i:s');

                if (in_array($dateheure, $datesheures)) {
                    $tmp[$dayOfWeek] = 1;
                }
            }
        }

        $tmp2 = array();
        foreach ($tmp as $key => $value) {
            $tmp2[] = $key;
        }
        $daysOfWeekStr = implode(";", $tmp2);

        return $daysOfWeekStr;
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

        $garderies = array();
        $daysOfWeek = explode(';', $daysOfWeekString);
        $garderies_all = $this->daysOfWeek->getListJoursGarderie();

        foreach ($daysOfWeek as $dayOfWeek)
        {
            foreach ($garderies_all[$dayOfWeek] as $dateheure) {
                $garderie = new Garderie();
                $garderie->setEleve($this->eleve);
                $garderie->setDateHeure(new \DateTime($dateheure));
                $this->manager->persist($garderie);
                $garderies[] = $garderie;
            }
        }

        return $garderies;
    }
}

