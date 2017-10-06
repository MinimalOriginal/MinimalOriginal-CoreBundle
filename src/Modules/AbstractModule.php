<?php

namespace MinimalOriginal\CoreBundle\Modules;

use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractModule implements ModuleInterface{

  private $em;

  public function __construct(EntityManagerInterface $em)
  {
      $this->em = $em;
  }

  public function createModel(){
    $model_class = $this->getEntityClass();
    return new $model_class();
  }

  public function updateModel($model, $andFlush = true){
    $this->em->persist($model);
    if ($andFlush) {
        $this->em->flush();
    }
  }

  public function removeModel($model, $andFlush = true){
    $this->em->remove($model);
    if ($andFlush) {
        $this->em->flush();
    }
  }

}
