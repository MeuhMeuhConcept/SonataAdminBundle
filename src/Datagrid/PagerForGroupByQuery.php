<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
use Sonata\DoctrineORMAdminBundle\Datagrid\Pager as BasePager;

class PagerForGroupByQuery extends BasePager
{
    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
    }
    /**
     * {@inheritdoc}
     */
    public function computeNbResult()
    {
        $cloneQuery = clone $this->getQuery();

        $cloneQuery->select(sprintf('%s.%s', $cloneQuery->getRootAlias(), current($this->getCountColumn())));

        $cloneQuery->resetDQLPart('orderBy');

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('cnt', 'count');
        $countQuery = $this->entityManager->createNativeQuery(
            sprintf(
                'SELECT COUNT(*) as cnt FROM (%s) AS cloneQuery;',
                $cloneQuery->getQuery()->getSQL()
            ),
            $rsm
        );

        $parameters = $this->getQuery()->getParameters();

        if (count($parameters) > 0) {
            $i = 0;
            foreach ($parameters as $parameter) {
                // Native Query use numeric key for parameters (base 1)
                $countQuery->setParameter(++$i, $parameter->getValue());
            }
        }

        return $countQuery->getSingleResult()['count'];
    }
}
