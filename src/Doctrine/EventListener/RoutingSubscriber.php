<?php

namespace MinimalOriginal\CoreBundle\Doctrine\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use MinimalOriginal\CoreBundle\Entity\EntityRoutedInterface;

use MinimalOriginal\CoreBundle\Entity\Routing;

class RoutingSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postLoad',
        );
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof EntityRoutedInterface ) {
          $objectManager = $args->getObjectManager();

          $menu_routing = new Routing();
          $menu_routing->setEntity(get_class($entity));
          $menu_routing->setEntityId($entity->getId());

          $objectManager->persist($menu_routing);
          $objectManager->flush();
        }
    }
    /**
     * Post load entity.
     *
     * @param LifecycleEventArgs $args
     */
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if (true === ($entity instanceof Routing)) {

          $objectManager = $args->getObjectManager();
          if( null !== ($object = $objectManager->getReference($entity->getEntity(), $entity->getEntityId())) ){
            $entity->setObject($object);
          }
        }
    }
}
