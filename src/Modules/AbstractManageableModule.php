<?php

namespace MinimalOriginal\CoreBundle\Modules;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class AbstractManageableModule extends AbstractModule implements ManageableModuleInterface{

  private $em;
  protected $informations;

  public function __construct(EntityManagerInterface $em)
  {
      $this->em = $em;
      parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public function getInformations(){
    return $this->informations;
  }

  /**
   * {@inheritdoc}
   */
  public function createModel(){
    $model_class = $this->getEntityClass();
    return new $model_class();
  }

  /**
   * {@inheritdoc}
   */
  public function updateModel($model, $andFlush = true){
    $this->em->persist($model);
    if ($andFlush) {
        $this->em->flush();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function removeModel($model, $andFlush = true){
    $this->em->remove($model);
    if ($andFlush) {
        $this->em->flush();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getParent(){
    return null;
  }

}
