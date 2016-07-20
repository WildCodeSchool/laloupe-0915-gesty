<?php
namespace Application\Sonata\UserBundle\EventListener;

use \Doctrine\ORM\Event\PreUpdateEventArgs;

class EditProfileListener
{
    private $mailer;
    private $senderAddress;

    private $propertyInfos = [
        'phone'         => 'Téléphone',
        'adresse'       => 'Adresse postale',
        'codePostal'    => 'Code postal',
        'commune'       => 'Ville',
        'caf'           => "Numéro d'allocataire CAF"
        ];

    public function __construct($mailer, $senderAddress)
    {
        $this->mailer = $mailer;
        $this->senderAddress = $senderAddress;
    }

    public function preUpdate(PreUpdateEventArgs $event)
    {
        /**
         * SEND MAIL ON PROFILE CHANGES
         */
        if ($this->hasChanged($event))
        {

            $user = $event->getEntity();

            /**
             * @var \Application\Sonata\UserBundle\Entity\User $admin
             */
            $admins = $event->getObjectManager()
                        ->getRepository('Application\Sonata\UserBundle\Entity\User')->findAll();
            $emailsDest = [];
            foreach ($admins as $admin) {
                if ($admin->hasRole('ROLE_SUPER_ADMIN') || $admin->hasRole('ROLE_SONATA_ADMIN') ) {
                    $emailsDest[] = $admin->getEmail();
                }
            }


            $message = \Swift_Message::newInstance()
                ->setSubject('Changements dans le profil de ' . \strtoupper($user->getLastname()).' '.$user->getFirstname())
                ->setFrom($this->senderAddress)
                ->setTo($emailsDest)
                ->setBody(
                    $this->getMailBody($event->getEntityChangeSet(), $user),
                    'text/html'
                );

            $this->mailer->send($message);
        }
    }


    private function hasChanged(PreUpdateEventArgs $event)
    {
        $hasChanged = FALSE;

        foreach ($this->propertyInfos as $property => $value) {
            if ($event->hasChangedField($property)
                && !empty($event->getOldValue($property))
                && \trim($event->getOldValue($property))!==\trim($event->getNewValue($property)) // fix bug on "codePostal"
            ) {
                $hasChanged = TRUE;
            }
        }

        return $hasChanged;
    }


    private function getMailBody($changes, $user)
    {
        $result = "<p>Notification du serveur Gesty : </p>";
        $result .= '<p>Changements dans le profil de '. \strtoupper($user->getLastname()).' '.$user->getFirstname() .'</p><br /><br />';

        foreach ($changes as $property => $change) {
            if (\array_key_exists($property, $this->propertyInfos) && $change[0] != $change[1]) {
                $result .= "<strong>".$this->propertyInfos[$property]." :</strong> ".$change[0]." --> ".$change[1]. "<br/><br/>";
            }
        }


        return $result;
    }
}


