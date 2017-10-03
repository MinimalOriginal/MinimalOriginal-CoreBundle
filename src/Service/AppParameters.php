<?php

namespace MinimalOriginal\CoreBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class AppParameters
{
  private $em;
  private $parameters;
  private $siteName;

    /**
    * @param EntityManagerInterface $em
    */
    public function __construct(EntityManagerInterface $em)
    {
      $this->em = $em;
      $this->parameters = new ParameterBag();

      $repo = $this->em->getRepository('MinimalOriginal\\CoreBundle\\Entity\\App');
      if( null !== ($parameters = $repo->findAll()) ){
        foreach($parameters as $parameter){
          $this->parameters->set($parameter->getAttr(), $parameter->getValue());
        }
      }
    }
    public function __call($name, $arguments = array()){
      return $this->parameters->get($name,null);
    }
  }
