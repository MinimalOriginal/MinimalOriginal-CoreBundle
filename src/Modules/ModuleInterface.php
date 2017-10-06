<?php

namespace MinimalOriginal\CoreBundle\Modules;

use MinimalOriginal\CoreBundle\Entity\EntityRoutedInterface;

interface ModuleInterface{

  public function init();

  public function getInformations();

}
