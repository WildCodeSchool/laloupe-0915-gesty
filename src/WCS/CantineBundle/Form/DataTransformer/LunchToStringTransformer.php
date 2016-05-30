<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Lunch;

class LunchToStringTransformer implements DataTransformerInterface
{

    private $manager;
    private $eleve;

    public function __construct(ObjectManager $manager, Eleve $eleve)
    {
        $this->manager = $manager;
        $this->eleve = $eleve;
    }

    /**
     * transforme recoit une liste de "lunch" de l'élève
     * récupère la date de chacun, puis renvoit une chaine
     * contenant uniquement les dates, formattées (Y-m-d) et séparées par un ";"
     *
     * @param  array $lunches
     * @return string
     */
    public function transform($lunches)
    {
        if (null === $lunches) {
            return '';
        }

        $tmp = array();
        foreach ($lunches as $lunch) {
            $tmp[] = $lunch->getDate()->format('Y-m-d');
        }
        $datesString = implode(";", $tmp);

        return $datesString;
    }

    /**
     * Récupère une chaine de dates formattées (Y-m-d) séparées par un ";"
     * et renvoit une liste de "lunch" pour l'élève pour chacune des dates.
     *
     * @param  string $datesString dates formattée Y-m-d séparées par un ";"
     * @return array Lunch renvoit une liste d'entité "Lunch" pour cet élève ou une liste vide
     */
    public function reverseTransform($datesString)
    {
        if (empty($datesString)) {
            return array();
        }

        $lunches = array();
        $dates = explode(';', $datesString);

        $lunchCurrents = $this->manager->getRepository("WCSCantineBundle:Lunch")->findByEleve($this->eleve);
        foreach ($dates as $date)
        {
            // si la réservation est déjà présente,
            // on se contente de l'ajouter dans la liste
            // sinon on créé une nouvelle réservation
            $dateT = new \DateTime($date);
            $found = false;
            foreach($lunchCurrents as $current) {
                if ($current->getDate()==$dateT) {
                    $lunch = $current;
                    $found = true;
                }
            }
            if (!$found) {
                $lunch = new Lunch();
                $lunch->setEleve($this->eleve);
                $lunch->setDate($dateT);
                $lunch->setStatus('0');
                $this->manager->persist($lunch);
            }
            $lunches[] = $lunch;
        }

        return $lunches;
    }
}
