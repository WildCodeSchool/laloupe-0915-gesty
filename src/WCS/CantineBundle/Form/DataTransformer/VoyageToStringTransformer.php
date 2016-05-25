<?php
namespace WCS\CantineBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\Voyage;

class VoyageToStringTransformer implements DataTransformerInterface
{

    private $manager;
    private $eleve;

    public function __construct(ObjectManager $manager, Eleve $eleve)
    {
        $this->manager = $manager;
        $this->eleve = $eleve;
    }

    /**
     * transforme recoit une liste de "Voyages" de l'élève
     * récupère l'id de chaque voyage, puis renvoit une chaine
     * contenant uniquement les identifiants séparées par un ";"
     *
     * @param  array $voyages
     * @return string
     */
    public function transform($voyages)
    {
        if (null === $voyages) {
            return '';
        }

        $tmp = array();
        foreach ($voyages as $voyage) {
            $tmp[] = $voyage->getId();
        }
        $datesString = implode(";", $tmp);

        return $datesString;
    }

    /**
     * Récupère une chaine d'identifiants séparées par un ";"
     * et renvoit une liste de "voyages" pour l'élève pour chacun des id.
     *
     * @param  string $datesString identifiants séparées par un ";"
     * @return array de voyages renvoit une liste d'entité "voyage" pour cet élève ou une liste vide
     */
    public function reverseTransform($datesString)
    {
        if (empty($datesString)) {
            return array();
        }

        $voyages = array();
        $ids = explode(';', $datesString);

        $voyagesCurrents = $this->manager->getRepository("WCSCantineBundle:Voyage")->findByEleve($this->eleve);
        foreach ($ids as $id)
        {
            // si la réservation est déjà présente,
            // on se contente de l'ajouter dans la liste
            // sinon on créé une nouvelle réservation
            $found = false;
            foreach($voyagesCurrents as $current) {
                if ($current->getId()==$id) {
                    $voyage = $current;
                    $found = true;
                }
            }
            if (!$found) {
                $voyage = new Voyage();
                $voyage->addEleve($this->eleve);
                $this->manager->persist($voyage);
            }
            $voyages[] = $voyage;
        }

        return $voyages;
    }
}
