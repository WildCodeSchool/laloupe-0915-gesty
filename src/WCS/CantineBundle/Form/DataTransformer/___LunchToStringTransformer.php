<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
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
        $datesString = '';
        $tmp = array();

        if (null !== $lunches) {
            foreach ($lunches as $lunch) {
                $tmp[] = $lunch->getDate()->format('Y-m-d');
            }
            $datesString = implode(";", $tmp);
        }

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
        $lunches = array();
        $dates = explode(';', $datesString);
        foreach ($dates as $date)
        {
            /*
            if ($date != '') {

                // récupère le repas pour cette date, si elle avait déjà été
                // enregistrée en base de donnée
                $lunch = $this->manager
                    ->getRepository('WCSCantineBundle:Lunch')
                    ->findByDateAndEleve($date, $this->eleve)
                ;
                // si elle était déjà enregistrée, on l'ajoute à la liste
                // des "lunch"
                if ($lunch)
                {
                    $lunches[] = $lunch[0];
                }
                // sinon on crée une nouvelle instance de "lunch" avec
                // cette nouvelle date.
                else
                {
                    $lunch = new Lunch();
                    $lunch->setDate(new \DateTime($date));
                    $lunch->setEleve($this->eleve);
                    $lunch->setStatus('O');
                    $this->manager->persist($lunch);
                    $lunches[] = $lunch;
                }
            }
            */

            $lunch = new Lunch();
            $lunch->setDate(new \DateTime($date));
            $lunch->setEleve($this->eleve);
            $lunch->setStatus('0');

            $lunches[] = $lunch;
        }

        return $lunches;
    }
    /*
    public function reverseTransform($dates)
    {
        $lunches = array();
        foreach (explode(';', $dates) as $date)
        {
            if ($date != '') {
                $lunch = $this->manager
                    ->getRepository('WCSCantineBundle:Lunch')
                    ->findByDateAndEleve($date, $this->eleve)
                ;
                if ($lunch)
                {
                    $lunches[] = $lunch[0];
                }
                else
                {
                    $lunch = new Lunch();
                    $lunch->setDate(new \DateTime($date));
                    $lunch->setEleve($this->eleve);
                    $lunch->setStatus('O');
                    $this->manager->persist($lunch);
                    $lunches[] = $lunch;
                }
            }
        }

        return $lunches;
    }
    */
}
