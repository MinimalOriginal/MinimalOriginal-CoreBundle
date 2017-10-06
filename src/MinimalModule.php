<?php

namespace MinimalOriginal\CoreBundle;

use MinimalOriginal\CoreBundle\Modules\AbstractManageableModule;

use MinimalOriginal\CoreBundle\Form\AppType;
use MinimalOriginal\CoreBundle\Entity\App;

class MinimalModule extends AbstractManageableModule{

  /**
   * {@inheritdoc}
   */
  public function init(){
    $this->informations->set('name', 'core');
    $this->informations->set('title', 'Paramètres');
    $this->informations->set('description', "Paramétrez votre site.");
    $this->informations->set('icon', "ion-ios-gear-outline");
    return $this;
  }

  public function getEntityClass(){
    return App::class;
  }

  public function getFormTypeClass(){
    return AppType::class;
  }

}
