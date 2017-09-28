<?php

namespace MinimalOriginal\CoreBundle;

use MinimalOriginal\CoreBundle\Modules\ModuleInterface;

use MinimalOriginal\CoreBundle\Form\AppType;
use MinimalOriginal\CoreBundle\Entity\App;

class MinimalModule implements ModuleInterface{

  public function getName(){
    return 'core';
  }

  public function getTitle(){
    return "Paramètres";
  }

  public function getDescription(){
    return "Paramétrez votre site.";
  }

  public function getEntityClass(){
    return App::class;
  }

  public function getFormTypeClass(){
    return AppType::class;
  }

}
