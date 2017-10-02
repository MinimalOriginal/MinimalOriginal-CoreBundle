<?php

namespace MinimalOriginal\CoreBundle\Routing;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use Sensio\Bundle\FrameworkExtraBundle\Routing\AnnotatedRouteControllerLoader;

class AnnotationClassLoader extends AnnotatedRouteControllerLoader
{
    private $em;

    /**
    * @param Reader $reader
    * @param EntityManagerInterface $em
    */
    public function __construct(Reader $reader, EntityManagerInterface $em)
    {
      parent::__construct($reader);
      $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function load($class, $type = null)
    {
        $routes = parent::load($class,$type);
        if( null !== $routes->get('minimal_front_home') ){
          $repo = $this->em->getRepository('MinimalOriginal\\CoreBundle\\Entity\\App');
          if(null !== ($app = $repo->findOneBy(array('attr' => 'home-page')) )){
            $repo = $this->em->getRepository('MinimalOriginal\\CoreBundle\\Entity\\Routing');
            $routing = $repo->findOneById($app->getValue());
            if( null !== $routing){
                $routes->remove('minimal_front_home');
            }
          }
        }elseif( null === $routes->get('minimal_front_home') ){
          $repo = $this->em->getRepository('MinimalOriginal\\CoreBundle\\Entity\\App');
          if(null !== ($app = $repo->findOneBy(array('attr' => 'home-page')) )){
            $repo = $this->em->getRepository('MinimalOriginal\\CoreBundle\\Entity\\Routing');
            if( null !== $app->getValue() ){
              $routing = $repo->findOneById($app->getValue());

              if( null !== $routes->get($routing->getRoute())){
                  $defaults = array_merge($routes->get($routing->getRoute())->getDefaults(),$routing->getRouteParams());
                  $route = new Route('/', $defaults);

                  $routes->add('minimal_front_home', $route);
              }
            }

          }
        }



        $this->loaded = true;

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && preg_match('/^(?:\\\\?[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)+$/', $resource) && (!$type || 'minimal_annotation' === $type);
    }

}
