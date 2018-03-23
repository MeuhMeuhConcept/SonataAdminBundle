<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\DoctrineORMAdminBundle\Builder\DatagridBuilder as SonataDatagridBuilder;
use Symfony\Component\Form\Extension\Core\Type\FormType;

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

    /**
     * Same function as parent but who use local Datagird class
     */
    public function getBaseDatagrid(AdminInterface $admin, array $values = [])
    {
        $pager = $this->getPager($admin->getPagerType());

        $pager->setCountColumn($admin->getModelManager()->getIdentifierFieldNames($admin->getClass()));

        $defaultOptions = [];
        if ($this->csrfTokenEnabled) {
            $defaultOptions['csrf_protection'] = false;
        }

        $formBuilder = $this->formFactory->createNamedBuilder('filter', FormType::class, [], $defaultOptions);
dump('tintin');
        return new Datagrid($admin->createQuery(), $admin->getList(), $pager, $formBuilder, $values);
    }
}
