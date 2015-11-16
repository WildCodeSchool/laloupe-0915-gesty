<?php

namespace Gesty\GestyBundle\Form\Handler;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;


class UserHandler
{
    protected $form;
    protected $request;
    protected $em;
    protected $security;

    /**
     * @param Form $form
     * @param Request $request
     */
    public function __construct(Form $form, Request $request, EntityManager $em, SecurityContext $security)
    {
        $this->form = $form;
        $this->request = $request;
        $this->em = $em;
        $this->security= $security;
    }

    /**
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);

        if($this->request->isMethod('post') && $this->form->isValid()) {
            $this->onSuccess();
            return true;

        }
        return false;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     *
     */
    protected function onSuccess()
    {
        $sheet = $this->form->getData();
        $sheet->setuser($this->security->getToken()->getUser());
        $this->em->persist($sheet);
        $this->em->flush();
    }

}