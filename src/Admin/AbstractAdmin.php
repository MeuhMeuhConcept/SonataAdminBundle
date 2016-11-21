<?php

namespace MMC\SonataAdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin as BaseAbstractAdmin;

class AbstractAdmin extends BaseAbstractAdmin
{
    public function configure()
    {
        parent:: configure();

        unset($this->listModes['mosaic']);

        $this->setTemplate('button_delete', 'MMCSonataAdminBundle:Admin:button_delete.html.twig');
    }

    public function getExportFormats()
    {
        return ['csv', 'xls'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureActionButtons($action, $object = null)
    {
        $list = [];

        if (in_array($action, ['tree', 'show', 'edit', 'delete', 'list', 'batch'])
            && $this->hasAccess('create')
            && $this->hasRoute('create')
        ) {
            $list['create'] = [
                'template' => $this->getTemplate('button_create'),
            ];
        }

        if (in_array($action, ['show', 'acl', 'history'])
            && $this->canAccessObject('edit', $object)
            && $this->hasRoute('edit')
        ) {
            $list['edit'] = [
                'template' => $this->getTemplate('button_edit'),
            ];
        }

        if (in_array($action, ['show', 'edit', 'acl'])
            && $this->canAccessObject('history', $object)
            && $this->hasRoute('history')
        ) {
            $list['history'] = [
                'template' => $this->getTemplate('button_history'),
            ];
        }

        if (in_array($action, ['edit', 'history'])
            && $this->isAclEnabled()
            && $this->canAccessObject('acl', $object)
            && $this->hasRoute('acl')
        ) {
            $list['acl'] = [
                'template' => $this->getTemplate('button_acl'),
            ];
        }

        if (in_array($action, ['edit', 'delete', 'history', 'acl'])
            && $this->canAccessObject('show', $object)
            && count($this->getShow()) > 0
            && $this->hasRoute('show')
        ) {
            $list['show'] = [
                'template' => $this->getTemplate('button_show'),
            ];
        }

        if (in_array($action, ['show', 'edit'])
            && $this->hasAccess('delete')
            && $this->hasRoute('delete')
        ) {
            $list['delete'] = [
                'template' => $this->getTemplate('button_delete'),
            ];
        }

        if (in_array($action, ['create', 'show', 'edit', 'delete', 'acl', 'batch'])
            && $this->hasAccess('list')
            && $this->hasRoute('list')
        ) {
            $list['list'] = [
                'template' => $this->getTemplate('button_list'),
            ];
        }

        return $list;
    }
}
