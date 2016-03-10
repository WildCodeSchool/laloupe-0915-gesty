<?php
// src/WCS/CantineBundle/Form/DataTransformer/LunchToStringTransformer.php
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
     * Transforms an object (lunch) to a string (dates).
     *
     * @param  Lunch|null $lunches
     * @return string
     */
    public function transform($lunches)
    {
        if (null === $lunches) {
            return '';
        }
        else
        {
            $dates = '';
            foreach ($lunches as $lunch) {
                $dates .= date_format($lunch->getDate(), ('Y-m-d')).';';
            }
            return $dates;
        }
    }

    /**
     * Transforms a string (dates) to an array (lunches).
     *
     * @param  string $dates
     * @return Lunch|null
     */
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
                    $this->manager->persist($lunch);
                    $lunches[] = $lunch;
                }
            }
        }

        return $lunches;
    }
}
