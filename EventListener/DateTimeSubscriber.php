<?php

namespace PN\ServiceBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PN\ServiceBundle\Interfaces\DateTimeInterface;
use PN\ServiceBundle\Service\UserService;
use Psr\Container\ContainerInterface;

class DateTimeSubscriber implements EventSubscriber
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof DateTimeInterface) {
            $username = $this->container->get(UserService::class)->getUserName();
            $entity->setModified(new \DateTime(date('Y-m-d H:i:s')));
            $entity->setModifiedBy($username);

        }
    }


    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof DateTimeInterface) {
            $username = $this->container->get(UserService::class)->getUserName();

            $entity->setModified(new \DateTime(date('Y-m-d H:i:s')));
            $entity->setModifiedBy($username);

            if ($entity->getCreated() == null) {
                $entity->setCreated(new \DateTime(date('Y-m-d H:i:s')));
            }
            if ($entity->getCreator() == null) {
                $entity->setCreator($username);
            }
        }
    }

}