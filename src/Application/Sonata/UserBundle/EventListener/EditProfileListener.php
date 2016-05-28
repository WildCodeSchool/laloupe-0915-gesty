<?php
// src/Application/Sonata/UserBundle/EventListener/EditProfileListener.php
namespace Application\Sonata\UserBundle\EventListener;


class EditProfileListener
{
    private $mailer;

    public function preUpdate(\Doctrine\ORM\Event\PreUpdateEventArgs $user)
    {
        /**
         * SEND MAIL ON PROFILE CHANGES
         */
        if (($user->hasChangedField('phone') && $user->getOldValue('phone') != NULL) ||
            $user->hasChangedField('commune') && $user->getOldValue('commune') != NULL ||
            $user->hasChangedField('caf') && $user->getOldValue('caf') != NULL
        ) {
            /*
             Code suivant à modifier
            l'adresse est en dur
            */
            /*
            $em = $user->getObjectManager();
            $admins = $em->getRepository('Application\Sonata\UserBundle\Entity\User')->findAll();
            $mailsArray = [];
            foreach ($admins as $admin) {
                $mailsArray[] = $admin->getEmail();
            }
            */

            $message = \Swift_Message::newInstance()
                ->setSubject('Changements dans le profil de ' . $user->getEntity())
                ->setFrom('cryptyo@gmail.com')
                ->setTo('bruchonsev@gmail.com')
                ->setBody(
                    $this->getMailBody($user->getEntityChangeSet(), $user->getEntity()),
                    'text/html'
                );
            $this->mailer->send($message);
        }
    }


    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    private function getMailBody($changes, $user)
    {
        $result = '<h3>Changements dans le profil de '. $user .'</h3>';

        foreach ($changes as $property=>$change) {
            if($property === "phone" ||
                $property === "commune" ||
                $property === "caf" )
            {
                $result .= "<strong>".$property." :</strong> ".$change[0]." --> ".$change[1]. '<br/><br/>';
            }
        }
        return $result;
    }
}


