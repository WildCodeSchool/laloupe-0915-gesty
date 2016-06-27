<?php
/**
 * Change upload path before processing the upload.
 *
 * @see
 *
 * http://symfony.com/doc/2.7/cookbook/doctrine/event_listeners_subscribers.html
 * http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/events.html#entity-listeners
 */

namespace Application\Sonata\UserBundle\EventListener;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Application\Sonata\UserBundle\Entity\User;
use WCS\CantineBundle\Entity\SchoolYear;

class UserUploadListener implements EventSubscriber
{
    private $upload_folder;
    private $upload_absolute_path;

    public function __construct($upload_folder, $upload_absolute_path)
    {
        $this->upload_folder        = $upload_folder;
        $this->upload_absolute_path = $upload_absolute_path;
    }

    public function getSubscribedEvents()
    {
        return array(Events::prePersist, Events::preUpdate, Events::postLoad);
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updatePaths($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->updatePaths($args);
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $this->updatePaths($args);
    }

    private function updatePaths(LifecycleEventArgs $args)
    {
        $obj = $args->getObject();
        if ($obj instanceof User) {
            $obj->setUploadFolder($this->upload_folder);
            $obj->setUploadAbsolutePath($this->upload_absolute_path);
        }

        if ($obj instanceof SchoolYear) {
            $obj->setUploadAbsolutePath($this->upload_absolute_path);
        }
    }
}
