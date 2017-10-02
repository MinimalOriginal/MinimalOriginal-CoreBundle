<?php

namespace MinimalOriginal\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Tools\Pagination\CountWalker;

abstract class AbstractRepository extends EntityRepository{

  protected $queryFilter;
  protected $useOutputWalkers = false;
  protected $paginate = true;
  protected $alias;

  /**
   * Return exposed order type parameters
   *
   * @return array
   */
  public function getOrderExposed(){
    return array(
      'title' => "Titre",
      'date' => "Date de création",
      'updated' => "Date de mise à jour",
    );
  }

  /**
   * Set query filter
   *
   * @param QueryFilter $queryFilter
   *
   * @return AbstractRepository
   */
  public function setQueryFilter(QueryFilter $queryFilter){
    $this->queryFilter = $queryFilter;
    $this->queryFilter->addFilterExposed('order', $this->getOrderExposed());
    return $this;
  }

  /**
   * Return query filter
   *
   * @return QueryFilter
   */
  public function getQueryFilter(){
    if( null === $this->queryFilter){
      $this->queryFilter = new QueryFilter();
    }
    return $this->queryFilter;
  }

  /**
   * Checks is paginate.
   *
   * @param null|bool $paginate
   *
   * @return bool
   */
  public function isPaginate($paginate = null)
  {
      if (null !== $paginate) {
          $this->paginate = $paginate;
      }

      return $this->paginate;
  }

  public function findList(){
    $qb = $this->initializeQueryBuilder($this->generateAliasFromEntityName());

    $qb = $this->addTypeOn($qb);
    $qb = $this->addLimitOn($qb, $this->getQueryFilter()->getOffset(), $this->getQueryFilter()->getLimit());

    $qb = $this->addFilterBy($qb);
    $qb = $this->addOrderBy($qb);

    // if (true === $this->getQueryFilter()->getFilter('is_array', false)) {
    //     return $qb->getQuery()->getArrayResult();
    // }

    if (false === $this->isPaginate()) {
        return $paginator->getIterator()->getArrayCopy();
    }

    $paginator = $this->newPaginator($qb, $this->isPaginate());
    return $paginator;

  }

  /**
   * Same as createQueryBuilder
   */
  public function initializeQueryBuilder($alias)
  {
      return $this->createQueryBuilder($alias);
  }

  /**
   * Adds filter by type on query.
   *
   * @param QueryBuilder $qb
   *
   * @return QueryBuilder
   */
  protected function addTypeOn(QueryBuilder $qb)
  {
      return $qb;
  }
  /**
   * Adds filter by on query.
   *
   * @param QueryBuilder $qb
   *
   * @return QueryBuilder
   */
  protected function addFilterBy(QueryBuilder $qb)
  {
      // foreach ($this->getQueryFilter()->getFilters() as $key => $vals) {
      //     switch ($key) {
      //         case 'id':
      //         case 'ids':
      //             if (false === is_array($vals)) {
      //                 $vals = array($vals);
      //             }
      //
      //             $vals = array_filter($vals, function ($id) {
      //                 return true === is_numeric($id) && false === strpos($id, '.');
      //             });
      //
      //             if (1 === count($vals)) {
      //                 $qb->andWhere($qb->getRootAlias() . '.id = :filter_id')
      //                     ->setParameter('filter_id', current($vals));
      //             } else {
      //                 $qb->andWhere($qb->getRootAlias() . '.id IN (:filter_ids)')
      //                     ->setParameter('filter_ids', $vals);
      //             }
      //
      //             break;
      //         case 'not_id':
      //         case 'not_ids':
      //             if (false === is_array($vals)) {
      //                 $vals = array($vals);
      //             }
      //
      //             $vals = array_filter($vals, function ($id) {
      //                 return true === is_numeric($id) && false === strpos($id, '.');
      //             });
      //
      //             if (1 === count($vals)) {
      //                 $qb->andWhere($qb->getRootAlias() . '.id != :filter_not_id')
      //                     ->setParameter('filter_not_id', current($vals));
      //             } else {
      //                 $qb->andWhere($qb->getRootAlias() . '.id NOT IN (:filter_not_ids)')
      //                     ->setParameter('filter_not_ids', $vals);
      //             }
      //
      //             break;
      //     }
      // }

      return $qb;
  }

  /**
   * Adds limit on query.
   *
   * @param QueryBuilder $qb
   * @param integer      $offset
   * @param null|integer $limit
   *
   * @return QueryBuilder
   */
  protected function addLimitOn(QueryBuilder $qb, $offset, $limit = null)
  {
      $qb->setFirstResult($offset);

      if (null !== $limit) {
          $qb->setMaxResults($limit);
      }

      return $qb;
  }

  /**
   * Adds order by on query.
   *
   * @param QueryBuilder $qb
   *
   * @return QueryBuilder
   */
  protected function addOrderBy(QueryBuilder $qb)
  {
      switch ($this->getOrderType()) {
          case 'title':
              $qb->orderBy($qb->getRootAlias() . '.title', $this->getOrderDir());

              break;
          case 'updated':
              $qb->orderBy($qb->getRootAlias() . '.updated', $this->getOrderDir());

              break;
          case null:
          case 'date':
          case 'created':
              $qb->orderBy($qb->getRootAlias() . '.created', $this->getOrderDir());

              break;
      }
      $qb->addOrderBy($qb->getRootAlias() . '.id', $this->getOrderDir());

      return $qb;
  }

  /**
   * Return type
   *
   * @return string
   */
  public function getType(){
    return $this->getQueryFilter()->getType()?: null;
  }

  /**
   * Return order type
   *
   * @return string
   */
  public function getOrderType(){
    if( null === $this->getQueryFilter()->getOrderType()){
      $this->getQueryFilter()->setOrderType($this->getDefaultOrderType());
    }
    return $this->getQueryFilter()->getOrderType()?:$this->getDefaultOrderType();
  }

  /**
   * Return order direction
   *
   * @return string
   */
  public function getOrderDir(){
    if( null === $this->getQueryFilter()->getOrderDir()){
      $this->getQueryFilter()->setOrderDir($this->getDefaultOrderDir());
    }
    return $this->getQueryFilter()->getOrderDir()?:$this->getDefaultOrderDir();
  }

  /**
   * Returns default order type.
   *
   * @return string
   */
  public function getDefaultOrderType()
  {
      return 'date';
  }

  /**
   * Returns default order dir.
   *
   * @return string
   */
  public function getDefaultOrderDir()
  {
      switch ($this->getOrderType()) {
          case 'title':
          case 'name':
              return 'ASC';
      }

      return 'DESC';
  }

  /**
   * Creates new paginator.
   *
   * @param QueryBuilder $query
   * @param bool         $fetchJoinCollection
   *
   * @return Paginator
   */
  protected function newPaginator(QueryBuilder $query, $fetchJoinCollection = true)
  {
    if (true === ($query instanceof QueryBuilder)) {
        $query = $query->getQuery();
    }
    $query->setHint(CountWalker::HINT_DISTINCT, false);

    $paginator = new Paginator($query, $fetchJoinCollection);
    $paginator->setUseOutputWalkers($this->useOutputWalkers);

    return $paginator;
  }

  /**
   * Generate Alias from entity name with uppercase
   *
   * @return string
   */
  protected function generateAliasFromEntityName()
  {
      if (null !== $this->alias) {
          return $this->alias;
      }

      if (false === ($pos = strrpos($this->_entityName, '\\'))) {
          return '';
      }

      $words = preg_split('/(?=[A-Z])/', substr($this->_entityName, $pos + 1), -1, PREG_SPLIT_NO_EMPTY);
      $alias = '';

      foreach ($words as $word) {
          $alias .= strtolower(substr($word, 0, 1));
      }

      return $this->alias = $alias;
  }

}
