<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery as BaseProxyQuery;

/**
 * This class try to unify the query usage with Doctrine.
 */
class ProxyQuery extends BaseProxyQuery
{
    /**
     * {@inheritdoc}
     */
    public function execute(array $params = [], $hydrationMode = null)
    {
        return $this->getExecutableQuery()->execute($params, $hydrationMode);
    }

    public function getExecutableQuery()
    {
        // always clone the original queryBuilder
        $queryBuilder = clone $this->queryBuilder;

        // todo : check how doctrine behave, potential SQL injection here ...
        if ($this->getSortBy()) {
            $sortBy = $this->getSortBy();
            if (strpos($sortBy, '.') === false) { // add the current alias
                $sortBy = $queryBuilder->getRootAlias().'.'.$sortBy;
            }
            $queryBuilder->addOrderBy($sortBy, $this->getSortOrder());
        } else {
            $queryBuilder->resetDQLPart('orderBy');
        }

        return $this->getFixedQueryBuilder($queryBuilder)->getQuery();
    }
}
