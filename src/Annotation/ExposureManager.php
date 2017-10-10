<?php

namespace MinimalOriginal\CoreBundle\Annotation;

use Doctrine\Common\Annotations\Reader;

class ExposureManager{

  private $annotation_reader;

  /**
   *
   * @param Reader $annotation_reader
   *
   */
  public function __construct(Reader $annotation_reader){
    $this->annotation_reader = $annotation_reader;
  }

  /**
   *
   * @param string $entity_class
   * @param string $group
   *
   * @return array
   *
   */
  public function getExposedFields($entity_class, $group = null){

    if( null === $group ) return array();

    $reflect = new \ReflectionClass($entity_class);
    $properties = $reflect->getProperties();
    $exposed_properties = array();

    foreach ($properties as $property)
    {
        if( null !== ($annotation = $this->annotation_reader->getPropertyAnnotation($property,'MinimalOriginal\CoreBundle\Annotation\Exposure')) ){
          if( true === in_array($group, $annotation->getGroups()) ){
            $exposed_properties[$property->getName()] = array('name' => $annotation->getName(), 'type' => $annotation->getType());
          }
        }
    }
    return $exposed_properties;
  }
}
