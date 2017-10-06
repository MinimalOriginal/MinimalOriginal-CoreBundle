<?php

namespace MinimalOriginal\CoreBundle\Modules;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\ParameterBag;

use MinimalOriginal\CoreBundle\Entity\EntityRoutedInterface;

class ModuleList
{
    private $modules = array();
    private $entities = array();
    private $modules_children;

    public function __construct(){
      $this->modules_children = new ParameterBag();
    }

    /**
     * Sets modules.
     *
     * @param array $modules
     */
    public function setModules(array $modules)
    {
        foreach ($modules as $module) {
            $this->addModule($module);
        }
    }

    /**
     * Adds model.
     *
     * @param ModuleInterface $module
     */
    public function addModule(ModuleInterface $module)
    {
      $this->modules[$module->getInformations()->get('name')] = $module;
      $this->entities[$module->getEntityClass()] = $module;

      if( $module instanceof ManageableModuleInterface){
        if( null !== $module->getParent() ){
          $parents = $module->getParent();
          if( false === is_array($parents)) $parents = array($parents);
          foreach($parents as $parent){
            if( false === $this->modules_children->has($parent) ) $this->modules_children->set($parent, new ParameterBag());
            $this->modules_children->get($parent)->set($module->getInformations()->get('name'), $module);
          }
        }
      }
    }

    /**
     * Returns module for this name.
     *
     * @param string $name
     *
     * @return ModuleInterface
     */
    public function getModule($name)
    {
        if (false === isset($this->modules[$name])) {
            throw new NotFoundHttpException(sprintf('Module "%s" introuvable', $name));
        }

        $module = $this->modules[$name];

        return $module;
    }

    /**
     * Returns module for entity class.
     *
     * @param string $entity_class
     *
     * @return ModuleInterface
     */
    public function getModuleForEntity($entity_class)
    {
        if (false === isset($this->entities[$entity_class])) {
            throw new NotFoundHttpException(sprintf('Entité "%s" introuvable', $entity_class));
        }

        $module = $this->entities[$entity_class];

        return $module;
    }

    /**
     * Returns modules
     *
     * @return array
    */
    public function getModules()
    {
        return $this->modules;
    }

    /**
     * Returns children of modules
     *
     * @return array
    */
    public function getModulesChildren()
    {
        return $this->modules_children;
    }

    /**
     * Get route for entity
     *
     * @param EntityRoutedInterface $entity
     * @param null|string $entity_class
     *
     * @return array
    */
    public function getRouteForEntity(EntityRoutedInterface $entity, $entity_class = null)
    {
      if( null === $entity_class ){
        $entity_class = get_class($entity);
      }
      $module = $this->getModuleForEntity($entity_class);

      return array(
        'route' => $module->getShowRoute(),
        'routeParameters' => $module->getShowRouteParams($entity),
      );

    }

}
