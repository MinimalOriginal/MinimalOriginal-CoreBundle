<?php

namespace MinimalOriginal\CoreBundle\Repository;

use Symfony\Component\HttpFoundation\Request;

class QueryFilter{

  private $request;
  private $page;
  private $limit;
  private $offset;
  private $orderType;
  private $orderDir;
  private $type;
  private $filtersExposed;

  const MIN_LIMIT = 1;
  const MAX_LIMIT = 150;
  const DEFAULT_LIMIT = 20;
  const DEFAULT_PAGE = 1;
  const DEFAULT_OFFSET = 0;

  /**
  * @param Request $request
  */
  public function __construct(Request $request = null)
  {
    if( null !== $request){
      $this->request = $request;
      $this->setPage($this->request->query->get('page', 1));
      $this->setLimit($this->request->query->get('limit', 20));
      $this->setOrderType($this->request->query->get('order', null));
      $this->setOrderDir($this->request->query->get('dir', null));
    }
  }

  /**
   * Return exposed order type parameters
   *
   * @return array
   */
  public function addFilterExposed($filter_name, $filter){
    $this->filtersExposed[$filter_name] = array_merge($this->getFilterExposed($filter_name), $filter);
    return $this;
  }

  /**
   * Return exposed filters
   *
   * @return array
   */
  public function getFiltersExposed($defaults = array()){
    return $this->filtersExposed?: $defaults;
  }

  /**
   * Return exposed filters
   *
   * @param string  $filter_name
   * @param array   $defaults
   *
   * @return array
   */
  public function getFilterExposed($filter_name, $defaults = array()){
    return true === isset($this->filtersExposed[$filter_name])? $this->filtersExposed[$filter_name] : $defaults;
  }

  /**
  * @param int $page
  *
  * @return QueryFilter
  */
  public function setPage($page)
  {
    $this->page = max($page, self::DEFAULT_PAGE);
    return $this;
  }

  /**
  * @return int
  */
  public function getPage()
  {
    return max($this->page, self::DEFAULT_PAGE);
  }

  /**
  * @param int $limit
  *
  * @return QueryFilter
  */
  public function setLimit($limit)
  {
    $this->limit = max(min($limit, self::MAX_LIMIT), self::MIN_LIMIT);
    return $this;
  }

  /**
  * @return int
  */
  public function getLimit()
  {
    return max(min($this->limit, self::MAX_LIMIT), self::MIN_LIMIT);
  }

  /**
  * @param string $type
  *
  * @return QueryFilter
  */
  public function setType($type = null)
  {
    $this->type = $type;
    return $this;
  }

  /**
  * @return string
  */
  public function getType()
  {
    return $this->type;
  }

  /**
  * @return int
  */
  public function getOffset()
  {
    $offset = ($this->getPage() - 1) * $this->getLimit();
    return max($offset, self::DEFAULT_OFFSET);
  }

  /**
     * Sets order_type.
     *
     * @param null|string $orderType
     */
    public function setOrderType($orderType = null)
    {
        $this->orderType = $orderType;
    }

    /**
     * Returns orderType.
     *
     * @param null|string $default
     *
     * @return null|string
     */
    public function getOrderType($default = null)
    {
        return $this->orderType ?: $default;
    }

    /**
     * Sets order_dir.
     *
     * @param null|string $orderFir
     */
    public function setOrderDir($orderDir = null)
    {
        if (false === in_array($orderDir, array('ASC', 'DESC'), true)) {
            $orderDir = null;
        }

        $this->orderDir = $orderDir ? strtoupper($orderDir) : null;
    }

    /**
     * Returns order_dir.
     *
     * @param null|string $default
     *
     * @return null|string
     */
    public function getOrderDir($default = null)
    {
        return $this->orderDir ?: $default;
    }

    /**
     * Returns route name.
     *
     * @param null|string $default
     *
     * @return null|string
     */
    public function getRouteName($default = null)
    {
        if (false === empty($routeName = $this->request->attributes->get('routeName'))) {
            return $routeName;
        }

        if (false === empty($routeName = $this->request->get('routeName'))) {
            return $routeName;
        }

        if (false === empty($routeName = $this->request->attributes->get('_route'))) {
            return $routeName;
        }

        return $default;
    }

    /**
     * Returns route params.
     *
     * @return array
     */
    public function getRouteParams()
    {
        $params = $this->request->attributes->get('_route_params',[]);

        if (false === isset($params['page'])) {
          if( $this->getPage() > 1 ) $params['page'] = $this->getPage();
        }

        if (false === isset($params['limit'])) {
          if( $this->getLimit() != self::DEFAULT_LIMIT ) $params['limit'] = $this->getLimit();
        }

        // if (false === isset($params['display'])) {
        //   if( $this->getDisplay() != 'default' ) $params['display'] = $this->getDisplay();
        // }

        if (false === isset($params['order'])) {
          if( null !== $this->getOrderType() ) $params['order'] = $this->getOrderType();
        }

        if (false === isset($params['dir'])) {
          if( null !== $this->getOrderDir() ) $params['dir'] = strtolower($this->getOrderDir());
        }

        // if (null !== ($category = $this->request->get('category', null))) {
        //     $params['category'] = $category;
        // }

        return $params;
    }

}
