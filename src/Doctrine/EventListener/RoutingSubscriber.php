<?php

namespace MinimalOriginal\CoreBundle\Doctrine\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

use MinimalOriginal\CoreBundle\Entity\EntityRoutedInterface;
use MinimalOriginal\CoreBundle\Modules\ModuleRoutedInterface;
use MinimalOriginal\CoreBundle\Modules\ModuleList;

use MinimalOriginal\CoreBundle\Entity\Routing;

class RoutingSubscriber implements EventSubscriber
{

    private $moduleList;

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'preRemove',
        );
    }

    /**
    * @param ModuleList $moduleList
    */
    public function __construct(ModuleList $moduleList){
      $this->moduleList = $moduleList;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
      $entity = $args->getObject();
      if ($entity instanceof EntityRoutedInterface ) {

        $objectManager = $args->getObjectManager();
        $module = $this->moduleList->getModuleForEntity(get_class($entity));

        if( $module instanceof ModuleRoutedInterface){

          $showRoute = $module->getShowRoute();
          $showRouteParams = $module->getShowRouteParams($entity);

          $repo = $objectManager->getRepository('MinimalOriginal\CoreBundle\Entity\Routing');
          if( null !== ($menu_routing = $repo->findOneBy(array('route'=>$showRoute, 'routeParams'=>$showRouteParams)))){
            $menu_routing->setTitle($entity->getTitle());
          }else{
            $menu_routing = new Routing();
            $menu_routing->setTitle($entity->getTitle());
            $menu_routing->setRoute($showRoute);
            $menu_routing->setRouteParams($showRouteParams);
          }

          $objectManager->persist($menu_routing);
          $objectManager->flush();
        }

      }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
      $entity = $args->getEntity();
      if ($entity instanceof EntityRoutedInterface ) {
        $objectManager = $args->getObjectManager();
        $repo = $objectManager->getRepository('MinimalOriginal\CoreBundle\Entity\Routing');

        $showRoute = $module->getShowRoute();
        $showRouteParams = $module->getShowRouteParams($entity);

        $routings= $repo->findBy(array('route'=>$showRoute, 'routeParams'=>$showRouteParams));
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
          $module = $this->moduleList->getModuleForEntity(get_class($entity));
          if( $module instanceof ModuleRoutedInterface){

            $showRoute = $module->getShowRoute();
            $showRouteParams = $module->getShowRouteParams($entity);

            $menu_routing = new Routing();
            $menu_routing->setTitle($entity->getTitle());
            $menu_routing->setRoute($showRoute);
            $menu_routing->setRouteParams($showRouteParams);

            $objectManager->persist($menu_routing);
            $objectManager->flush();
          }

        }
    }

}
