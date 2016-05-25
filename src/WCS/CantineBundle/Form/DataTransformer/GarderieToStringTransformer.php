<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Garderie;

class GarderieToStringTransformer implements DataTransformerInterface
{

    private $manager;
    private $eleve;

    public function __construct(ObjectManager $manager, Eleve $eleve)
    {
        $this->manager = $manager;
        $this->eleve = $eleve;
    }

    /**
     * transforme recoit une liste de "Garderie" de l'élève
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

        $tmp = array();
        foreach ($garderies as $garderie) {
            $tmp[] = $garderie->getDate()->format('Y-m-d H:i:s');
        }
        $datesString = implode(";", $tmp);

        return $datesString;
    }

    /**
     * Récupère une chaine de dates formattées (Y-m-d) séparées par un ";"
     * et renvoit une liste de "garderie" pour l'élève pour chacune des dates.
     *
     * @param  string $datesString dates formattée Y-m-d séparées par un ";"
     * @return array de garderies renvoit une liste d'entité "garderie" pour cet élève ou une liste vide
     */
    public function reverseTransform($datesString)
    {
        if (empty($datesString)) {
            return array();
        }

        $garderies = array();
        $dates = explode(';', $datesString);

        $garderieCurrents = $this->manager->getRepository("WCSCantineBundle:Garderie")->findByEleve($this->eleve);
        foreach ($dates as $date)
        {
            // si la réservation est déjà présente,
            // on se contente de l'ajouter dans la liste
            // sinon on créé une nouvelle réservation
            $dateT = new \DateTime($date);
            $found = false;
            foreach($garderieCurrents as $current) {
                if ($current->getDate()==$dateT) {
                    $garderie = $current;
                    $found = true;
                }
            }
            if (!$found) {
                $garderie = new Garderie();
                $garderie->setEleve($this->eleve);
                $garderie->setDate($dateT);
                $this->manager->persist($garderie);
            }
            $garderies[] = $garderie;
        }

        return $garderies;
    }
}
