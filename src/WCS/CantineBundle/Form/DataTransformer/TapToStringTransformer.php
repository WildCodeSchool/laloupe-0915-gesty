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

    public function __construct(ObjectManager $manager, Eleve $eleve)
    {
        $this->manager = $manager;
        $this->eleve = $eleve;
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

        $tmp = array();
        foreach ($taps as $tap) {
            $tmp[] = $tap->getDate()->format('Y-m-d H:i:s');
        }
        $datesString = implode(";", $tmp);

        return $datesString;
    }

    /**
     * Récupère une chaine de dates formattées (Y-m-d) séparées par un ";"
     * et renvoit une liste de "tap" pour l'élève pour chacune des dates.
     *
     * @param  string $datesString dates formattée Y-m-d séparées par un ";"
     * @return array de Taps renvoit une liste d'entité "Tap" pour cet élève ou une liste vide
     */
    public function reverseTransform($datesString)
    {
        if (empty($datesString)) {
            return array();
        }

        $taps = array();
        $dates = explode(';', $datesString);

        $tapCurrents = $this->manager->getRepository("WCSCantineBundle:Tap")->findByEleve($this->eleve);
        foreach ($dates as $date)
        {
            // si la réservation est déjà présente,
            // on se contente de l'ajouter dans la liste
            // sinon on créé une nouvelle réservation
            $dateT = new \DateTime($date);
            $found = false;
            foreach($tapCurrents as $current) {
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

        return $taps;
    }
}
