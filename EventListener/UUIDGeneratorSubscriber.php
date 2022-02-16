<?php

namespace PN\ServiceBundle\EventListener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PN\ServiceBundle\Interfaces\UUIDInterface;
use PN\ServiceBundle\Utils\General;

class UUIDGeneratorSubscriber implements EventSubscriberInterface
{

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof UUIDInterface) {
            $uuid = $this->getUniqueUUID($args);
            $entity->setUuid($uuid);
        }
    }

    private function getUniqueUUID(LifecycleEventArgs $args)
    {
        $em = $args->getObjectManager();
        $entity = $args->getObject();
        do {
            $randomString = (new General())->generateRandomString(8);
            $uuidIfExist = $em->getRepository(get_class($entity))->findOneBy(["uuid" => $randomString]);
        } while ($uuidIfExist == true);

        return $randomString;
    }
}
