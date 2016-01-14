<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use FOS\UserBundle\Model\UserInterface;

/**
 * Controller managing the registration
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationFOSUser1Controller extends \Sonata\UserBundle\Controller\RegistrationFOSUser1Controller
{
    public function registerAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        if ($user instanceof UserInterface && 'POST' === $this->container->get('request')->getMethod()) {
            $this->container->get('session')->getFlashBag()->set('sonata_user_error', 'sonata_user_already_authenticated');
            $url = $this->container->get('router')->generate('sonata_user_profile_show');
            return new RedirectResponse($url);
        }

        $form = $this->container->get('sonata.user.registration.form');
        $formHandler = $this->container->get('sonata.user.registration.form.handler');
        $confirmationEnabled = true;

        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled) {
                $this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
                $route = 'fos_user_registration_check_email';
            } else {
                $authUser = true;
                $route = $this->container->get('session')->get('sonata_basket_delivery_redirect', 'sonata_user_profile_show');
                $this->container->get('session')->remove('sonata_basket_delivery_redirect');
            }

            $this->setFlash('fos_user_success', 'Votre compte est bien enregistré. Veuillez vous rendre dans votre boîte mail pour activer votre compte.');
            $url = $this->container->get('session')->get('sonata_user_redirect_url');

            if (null === $url || "" === $url) {
                $url = $this->container->get('router')->generate($route);
            }

            $response = new RedirectResponse($url);

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }

        $this->container->get('session')->set('sonata_user_redirect_url', $this->container->get('request')->headers->get('referer'));

        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
    }
}



