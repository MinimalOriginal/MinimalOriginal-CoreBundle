<?php

namespace MinimalOriginal\CoreBundle\Modules;

use MinimalOriginal\CoreBundle\Entity\EntityRoutedInterface;

interface ManageableModuleInterface extends ModuleInterface {

  public function createModel();

  public function updateModel($model, $andFlush = true);

  public function removeModel($model, $andFlush = true);

}
