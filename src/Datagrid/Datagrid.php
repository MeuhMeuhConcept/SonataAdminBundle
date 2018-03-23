<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Sonata\AdminBundle\Datagrid\Datagrid as BaseDatagrid;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class Datagrid extends BaseDatagrid
{
    public function buildPager()
    {
        if ($this->bound) {
            return;
        }

        foreach ($this->getFilters() as $name => $filter) {
            list($type, $options) = $filter->getRenderSettings();

            $this->formBuilder->add($filter->getFormName(), $type, $options);
        }

        $hiddenType = HiddenType::class;

        $this->formBuilder->add('_sort_by', $hiddenType);
        $this->formBuilder->get('_sort_by')->addViewTransformer(new CallbackTransformer(
            function ($value) {
                return $value;
            },
            function ($value) {
                return $value instanceof FieldDescriptionInterface ? $value->getName() : $value;
            }
        ));

        $this->formBuilder->add('_sort_order', $hiddenType);
        $this->formBuilder->add('_page', $hiddenType);
        $this->formBuilder->add('_per_page', $hiddenType);

        $this->form = $this->formBuilder->getForm();
        $this->form->submit($this->values);

        $data = $this->form->getData();

        $firstConstraints = $this->query->getDqlPart('where');
        $this->query->resetDqlPart('where');

        foreach ($this->getFilters() as $name => $filter) {
            $this->values[$name] = isset($this->values[$name]) ? $this->values[$name] : null;
            $filterFormName = $filter->getFormName();
            if (isset($this->values[$filterFormName]['value']) && $this->values[$filterFormName]['value'] !== '') {
                $filter->apply($this->query, $data[$filterFormName]);
            }
        }

        if ($firstConstraints) {
            $filterConstraints = $this->query->getDqlPart('where');
            $this->query->resetDqlPart('where');
            $this->query->andWhere($firstConstraints)
                ->andWhere($filterConstraints);
        }

        if (isset($this->values['_sort_by'])) {
            if (!$this->values['_sort_by'] instanceof FieldDescriptionInterface) {
                throw new UnexpectedTypeException($this->values['_sort_by'], FieldDescriptionInterface::class);
            }

            if ($this->values['_sort_by']->isSortable()) {
                $this->query->setSortBy($this->values['_sort_by']->getSortParentAssociationMapping(), $this->values['_sort_by']->getSortFieldMapping());
                $this->query->setSortOrder(isset($this->values['_sort_order']) ? $this->values['_sort_order'] : null);
            }
        }

        $maxPerPage = 25;
        if (isset($this->values['_per_page'])) {
            if (isset($this->values['_per_page']['value'])) {
                $maxPerPage = $this->values['_per_page']['value'];
            } else {
                $maxPerPage = $this->values['_per_page'];
            }
        }
        $this->pager->setMaxPerPage($maxPerPage);

        $page = 1;
        if (isset($this->values['_page'])) {
            if (isset($this->values['_page']['value'])) {
                $page = $this->values['_page']['value'];
            } else {
                $page = $this->values['_page'];
            }
        }

        $this->pager->setPage($page);

        $this->pager->setQuery($this->query);
        $this->pager->init();

        $this->bound = true;
    }
}
