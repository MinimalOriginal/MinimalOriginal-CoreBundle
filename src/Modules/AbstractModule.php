<?php

namespace MinimalOriginal\CoreBundle\Modules;

use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractModule implements ModuleInterface{

  protected $informations;

  public function __construct()
  {
      $this->informations = new ParameterBag();
      $this->init();
  }

  /**
   * {@inheritdoc}
   */
  public function getInformations(){
    return $this->informations;
  }

}
