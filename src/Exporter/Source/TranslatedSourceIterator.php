<?php

namespace MMC\SonataAdminBundle\Exporter\Source;

use Doctrine\ORM\Query;
use Exporter\Source\SourceIteratorInterface;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection;
use Symfony\Component\Translation\Translator;

class TranslatedSourceIterator implements SourceIteratorInterface
{
    /**
     * @var \Doctrine\ORM\Query
     */
    protected $query;

    /**
     * @var \Doctrine\ORM\Internal\Hydration\IterableResult
     */
    protected $iterator;

    protected $translator;
    protected $translation_domain;
    protected $columns;

    public function __construct(
        Query $query,
        FieldDescriptionCollection $columns,
        $translator,
        $translation_domain
    ) {
        $this->query = clone $query;
        $this->query->setParameters($query->getParameters());
        foreach ($query->getHints() as $name => $value) {
            $this->query->setHint($name, $value);
        }

        $this->translator = $translator;
        $this->translation_domain = $translation_domain;
        $this->columns = $columns;
    }

    public function current()
    {
        $current = $this->iterator->current();

        $data = [];

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

            $value = $column->getValue($current[0]);

            if (isset($this->translator) && $column->getOption('translation_pattern')) {
                $value = $this->translator->trans(
                    sprintf($column->getOption('translation_pattern'), $value),
                    [],
                    $column->getTranslationDomain()
                );
            }

            $data[$translatedLabel] = $value;
        }

        $this->query->getEntityManager()->getUnitOfWork()->detach($current[0]);

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->iterator->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->iterator->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->iterator->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        if ($this->iterator) {
            throw new InvalidMethodCallException('Cannot rewind a Doctrine\ORM\Query');
        }

        $this->iterator = $this->query->iterate();
        $this->iterator->rewind();
    }
}
