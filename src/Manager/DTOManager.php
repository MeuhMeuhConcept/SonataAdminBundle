<?php

namespace MMC\SonataAdminBundle\Manager;

use MMC\SonataAdminBundle\Datagrid\DTOProxyQuery;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as BaseModelManager;

class DTOManager extends BaseModelManager
{
    /**
     * {@inheritdoc}
     */
    public function createQuery($class, $alias = 'o')
    {
        $repository = $this->getEntityManager($class)->getRepository($class);

        return new DTOProxyQuery($repository->createQueryBuilder($alias));
    }
}
