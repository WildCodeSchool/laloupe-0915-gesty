<?php
/**
 * Created by PhpStorm.
 * User: charlisev
 * Date: 23/01/16
 * Time: 12:01
 */
namespace Application\Sonata\UserBundle\EventListener;

use Application\Sonata\UserBundle\Entity\User;


class EmailListener
{
//    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
//        $this->mailer = $mailer;
    }

    public function oneSendMail(User $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Inscription')
            ->setFrom('cryptyo@gmail.com')
            ->setTo('bruchonsev@gmail.com')
            ->setBody(
                $this->renderView(
              //WCS/CantineBundle/views/User/registrationEmail.html.twig
//                    'User/registrationEmail.html.twig',
                    'User/registrationemail.html.twig',
                    array('name' => $user)
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);


    }
}
