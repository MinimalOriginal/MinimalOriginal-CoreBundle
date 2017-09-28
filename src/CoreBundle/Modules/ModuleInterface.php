<?php

namespace MinimalOriginal\CoreBundle\Modules;

use MinimalOriginal\CoreBundle\Entity\EntityRoutedInterface;

interface ModuleInterface{

  public function getName();

  public function getTitle();

  public function getDescription();

  public function getEntityClass();

  public function getFormTypeClass();

}
