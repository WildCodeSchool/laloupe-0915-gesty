<?php
namespace WCS\CantineBundle\Form\Handler;


use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use WCS\CantineBundle\Entity\Eleve;
use WCS\CantineBundle\Form\FormEntity\EleveFormEntity;


class EleveHandler
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
     * @param EntityManager $em
     * @param User $user
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
     * @param EleveFormEntity $eleveForm
     * @return boolean $confirmation
     * @throws \Exception
     */
    public function process(EleveFormEntity $eleveForm)
    {
        $this->form->handleRequest($this->request);
        if ($this->form->isValid()) {

            if (!$eleveForm->getCertifie())
                throw new \Exception('Toutes les autorisations doivent être cochées !');

            if (!($eleveForm->getNom() && $eleveForm->getPrenom()))
                throw new \Exception('Vous devez remplir le nom et prénom');

            if (!($eleveForm->getDateDeNaissance()))
                throw new \Exception('Vous devez renseigner la date de naissance de votre enfant !');

            $eleve = new Eleve();
            $eleve->setNom($eleveForm->getNom());
            $eleve->setPrenom($eleveForm->getPrenom());
            $eleve->setDateDeNaissance($eleveForm->getDateDeNaissance());
            $eleve->setDivision($eleveForm->getDivision());
            $eleve->setRegimeSansPorc(false);
            $eleve->setUser($this->user);

            $this->em->persist($eleve);
            $this->em->flush();

            return true;
        }
        return false;
    }
}
