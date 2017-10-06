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
            'preRemove',
        );
    }


    public function postUpdate(LifecycleEventArgs $args)
    {
      $entity = $args->getObject();
      if ($entity instanceof EntityRoutedInterface ) {

        $objectManager = $args->getObjectManager();


        $repo = $objectManager->getRepository('MinimalOriginal\CoreBundle\Entity\Routing');
        if( null !== ($menu_routing = $repo->findOneBy(array('route'=>$entity->getShowRoute()->get('route'), 'routeParams'=>$entity->getShowRoute()->get('params'))))){
          $menu_routing->setTitle($entity->getTitle());
        }else{
          $menu_routing = new Routing();
          $menu_routing->setTitle($entity->getTitle());
          $menu_routing->setRoute($entity->getShowRoute()->get('route'));
          $menu_routing->setRouteParams($entity->getShowRoute()->get('params'));
        }

        $objectManager->persist($menu_routing);
        $objectManager->flush();
      }

    }

    public function preRemove(LifecycleEventArgs $args)
    {
      $entity = $args->getEntity();
      if ($entity instanceof EntityRoutedInterface ) {
        $objectManager = $args->getObjectManager();
        $repo = $objectManager->getRepository('MinimalOriginal\CoreBundle\Entity\Routing');

        $routings= $repo->findBy(array('route'=>$entity->getShowRoute()->get('route'), 'routeParams'=>$entity->getShowRoute()->get('params')));
        foreach( $routings as $routing ){
          $objectManager->remove($routing);
        }
        $objectManager->flush();
      }
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof EntityRoutedInterface ) {
          $objectManager = $args->getObjectManager();

            $menu_routing = new Routing();
            $menu_routing->setTitle($entity->getTitle());
            $menu_routing->setRoute($entity->getShowRoute()->get('route'));
            $menu_routing->setRouteParams($entity->getShowRoute()->get('params'));

            $objectManager->persist($menu_routing);
            $objectManager->flush();

        }
    }

}
