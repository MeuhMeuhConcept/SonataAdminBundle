<?php

namespace MMC\SonataAdminBundle\Exporter\Source;

use Exporter\Source\SourceIteratorInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Translation\TranslatorInterface;

class DataSourceIterator implements SourceIteratorInterface
{
    protected $position = 0;
    protected $query;
    protected $items;
    protected $currentPage;
    protected $columns;
    protected $translator;
    protected $pageSize;

    public function __construct(
        ProxyQueryInterface $query,
        FieldDescriptionCollection $columns,
        TranslatorInterface $translator = null,
        $pageSize = 0
    ) {
        $this->query = $query;
        $this->columns = $columns;
        $this->translator = $translator;
        $this->pageSize = $pageSize;
    }

    protected function buildPage($page)
    {
        return $this->query
            ->setFirstResult($page * $this->pageSize)
            ->setMaxResults($this->pageSize)
            ->execute();
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        $item = $this->getItem($this->position);
        $data = []; // The returned dictionary

        foreach ($this->columns->getElements() as $column) {
            if (isset($this->translator)) {
                $translatedLabel = $this->translator->trans(
                    $column->getLabel(),
                    [],
                    $column->getTranslationDomain()
                );
            } else {
                $translatedLabel = $column->getLabel();
            }

            $value = $column->getValue($item);

            if (isset($this->translator) && $column->getOption('translation_pattern')) {
                $value = $this->translator->trans(
                    sprintf($column->getOption('translation_pattern'), $value),
                    [],
                    $column->getTranslationDomain()
                );
            }

            $data[$translatedLabel] = $value;
        }

        return $data;
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return $this->getItem($this->position) !== null;
    }

    protected function getItem($position)
    {
        if ($this->pageSize > 0) {
            $page = (int) floor($position / $this->pageSize);
            $pagePosition = $position % $this->pageSize;
        } else {
            $page = 0;
            $pagePosition = $position;
        }

        if ($this->currentPage !== $page) {
            $this->items = $this->buildPage($page);
            $this->currentPage = $page;
        }

        if (isset($this->items[$pagePosition])) {
            return $this->items[$pagePosition];
        }

        return;
    }
}
