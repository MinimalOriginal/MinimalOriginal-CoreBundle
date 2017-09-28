<?php

namespace MinimalOriginal\CoreBundle\Routing;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use MinimalOriginal\CoreBundle\Modules\ModuleList;

class ExtraLoader extends Loader
{
    private $loaded = false;
    private $em;
    private $moduleList;

    /**
    * @param EntityManagerInterface $em
    * @param ModuleList             $moduleList
    */
    public function __construct(EntityManagerInterface $em, ModuleList $moduleList)
    {
      $this->em = $em;
      $this->moduleList = $moduleList;
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }
        $routes = new RouteCollection();
        //Add dynamic home page route
        $defaults = array(
            '_controller' => 'MinimalCoreBundle:Default:index',
        );

        $repo = $this->em->getRepository('MinimalOriginal\\CoreBundle\\Entity\\App');
        if(null !== ($app = $repo->findOneBy(array('attr' => 'home-page')) )){
          $repo = $this->em->getRepository('MinimalOriginal\\CoreBundle\\Entity\\Routing');
          $routing = $repo->findOneById($app->getValue());
          
          if( null !== $routing){
            $home_route = $this->moduleList->getRouteForEntity($routing->getObject(), $routing->getEntity());
            $defaults = array_merge(array('_controller' => $home_route['_controller']),$home_route['routeParameters']);
          }

        }
        $route = new Route('/', $defaults);

        $routes->add('minimal_front_home', $route);

        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }
}
