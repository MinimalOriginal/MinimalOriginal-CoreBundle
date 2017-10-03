<?php

namespace MinimalOriginal\CoreBundle\Routing\Loader;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

use MinimalOriginal\CoreBundle\Routing\Annotation\Route as AnnotationRoute;
use MinimalOriginal\CoreBundle\Entity\Routing;

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
    protected function addRoute(RouteCollection $collection, $annot, $globals, \ReflectionClass $class, \ReflectionMethod $method)
    {
        parent::addRoute($collection, $annot, $globals, $class, $method);

        // Ajoute la route à la table de routing si elle a un titre
        if( $annot instanceof AnnotationRoute ){
          if( null !== $annot->getTitle() ){
            $repo = $this->em->getRepository('MinimalOriginal\\CoreBundle\\Entity\\Routing');
            if( null === $repo->findOneBy(array('route'=>$annot->getName())) ){
              $routing = new Routing();
              $routing->setTitle($annot->getTitle());
              $routing->setRoute($annot->getName());
              $this->em->persist($routing);
              $this->em->flush();

            }
          }
        }

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
            if( null !== $routing && 'minimal_front_home' !== $routing->getRoute()){
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
                  // Merge les defaults si route params en base
                  $defaults = $routes->get($routing->getRoute())->getDefaults();
                  if( true === is_array($routing->getRouteParams()) ){
                    $defaults = array_merge($defaults,$routing->getRouteParams());
                  }

                  // Ajoute la route home à la collection
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
