<?php
//src/WCS/CantineBundle/Form/Handler/EleveEditHandler

namespace WCS\CantineBundle\Form\Handler;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Entity\EleveRepository;
use WCS\CantineBundle\Entity\Lunch;
use WCS\CantineBundle\Form\Model\EleveEdit;


class EleveEditHandler
{
    protected $request;
    protected $form;
    protected $em;
    protected $user;

    /**
     * Initialize the handler with the form and the request
     *
     * @param Form $form
     * @param Request $request
     * @param $mailer
     *
     */
    public function __construct(Form $form, Request $request, EntityManager $em, User $user)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->user = $user;
    }


    /**
     * @param boolean $confirmation
     */
    public function process(EleveEdit $eleve)
    {
        $this->form->handleRequest($this->request);
        if ($this->form->isValid()) {

            $entity = new Eleve();
            $entity->setNom($eleve->getNom());
            $entity->setPrenom($eleve->getPrenom());
            $entity->setDateDeNaissance($eleve->getDateDeNaissance());
            $entity->setDivision($eleve->getDivision());
            $entity->setRegimeSansPorc($eleve->getRegimeSansPorc());
            $entity->setAllergie($eleve->getAllergie());
            $entity->setUser($this->user);
            $entity->setHabits($eleve->getHabits());

            // Lunches
            foreach (explode(';', $eleve->getDates()) as $date)
            {
                $lunches = [];
                $lunches->getLunchesByDateAndId($eleve->getId(), $date);

                foreach ($lunches as $lunch) {
                    if ($lunch != '') {
                        $this->em->remove($lunch);
                        $this->em->flush();
                    } else {
                        $lunch = new Lunch();
                        $lunch->setDate(new \DateTime($eleve->getDates()));
                        $lunch->setEleve($entity);
                        $this->em->persist($lunch);
                    }
                }
            }

            $this->em->persist($entity);
            $this->em->flush();

            return true;
        }
        return false;
    }
}
