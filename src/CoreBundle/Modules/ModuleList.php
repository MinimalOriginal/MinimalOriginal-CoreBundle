<?php

namespace MinimalOriginal\CoreBundle\Modules;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

use MinimalOriginal\CoreBundle\Entity\EntityRoutedInterface;
use MinimalOriginal\CoreBundle\Modules\ModuleInterface;

class ModuleList
{
    private $requestStack;
    private $modules = array();
    private $entities = array();

    /**
     * Constructors.
     *
     * @param RequestStack        $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
      $this->modules[$module->getName()] = $module;
      $this->entities[$module->getEntityClass()] = $module;
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

        // if (null !== ($request = $this->requestStack->getCurrentRequest())) {
        //     $module->setRequest($request);
        // }

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

        // if (null !== ($request = $this->requestStack->getCurrentRequest())) {
        //     $module->setRequest($request);
        // }

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
        '_controller' => $module->getShowController(),
        'route' => $module->getShowRoute(),
        'routeParameters' => $module->getShowRouteParams($entity),
      );

    }

}
