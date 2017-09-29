<?php

namespace MinimalOriginal\CoreBundle\HttpKernel\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

use MinimalOriginal\CoreBundle\Repository\QueryFilter;

class Listener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::CONTROLLER => array(
                array('checkController', -1024),
            ),
        );
    }


    /**
     * Event on check controller.
     *
     * @param FilterControllerEvent $event
     */
    public function checkController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        $request = $event->getRequest();

        if (true === is_array($controller)) {
            $r = new \ReflectionMethod($controller[0], $controller[1]);
        } elseif (true === is_object($controller) && true === is_callable($controller, '__invoke')) {
            $r = new \ReflectionMethod($controller, '__invoke');
        } else {
            $r = new \ReflectionFunction($controller);
        }


        foreach ($r->getParameters() as $param) {
            if (null !== ($rClass = $param->getClass())) {
                switch ($rClass->getName()) {
                    case QueryFilter::class:
                        $request->attributes->set($param->getName(), new QueryFilter($request));
                        break;
                }
            }
        }
    }

}
