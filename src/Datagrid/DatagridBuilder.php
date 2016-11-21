<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Sonata\DoctrineORMAdminBundle\Builder\DatagridBuilder as SonataDatagridBuilder;

class DatagridBuilder extends SonataDatagridBuilder
{
    protected $pagers = [];

    public function addPager($type, $pager)
    {
        $this->pagers[$type] = $pager;
    }

    protected function getPager($pagerType)
    {
        foreach ($this->pagers as $type => $pager) {
            if ($type == $pagerType) {
                return $pager;
            }
        }

        return parent::getPager($pagerType);
    }
}
