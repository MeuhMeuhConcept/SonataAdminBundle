<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Doctrine\ORM\QueryBuilder;

class DTOProxyQuery extends ProxyQuery
{
    /**
     * {@inheritdoc}
     */
    public function setSortBy($parentAssociationMappings, $fieldMapping)
    {
        $this->sortBy = $fieldMapping;

        return $this;
    }

    /**
     * This method alters the query to return a clean set of object with a working
     * set of Object.
     *
     * @param QueryBuilder $queryBuilder
     *
     * @return QueryBuilder
     */
    protected function getFixedQueryBuilder(QueryBuilder $queryBuilder)
    {
        return $queryBuilder;
    }
}
