<?php

namespace MinimalOriginal\CoreBundle\Modules;

use MinimalOriginal\CoreBundle\Entity\EntityRoutedInterface;

interface ModuleRoutedInterface{

  public function getShowRoute();

  public function getShowRouteParams(EntityRoutedInterface $entity);

  public function getShowController();

  public function getShowControllerParams(EntityRoutedInterface $entity);

}
