<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Doctrine\Common\Collections\Collection;
use Sonata\AdminBundle\Admin\BaseFieldDescription;

class DTOFieldDescription extends BaseFieldDescription
{
    public function __construct($name, $type = null, array $options = [])
    {
        $this->setName($name);
        $this->setType($type ?: 'text');
        $this->setFieldName($name);
        $this->setOptions($options);
    }

    public function setAssociationMapping($associationMapping)
    {
        return $this;
    }

    public function setFieldMapping($fieldMapping)
    {
        return $this;
    }

    public function setParentAssociationMappings(array $parentAssociationMappings)
    {
        $this->parentAssociationMappings = $parentAssociationMappings;

        return $this;
    }

    public function getTargetEntity()
    {
        return;
    }

    public function isIdentifier()
    {
        return false;
    }

    public function getValue($object)
    {
        if (is_array($this->parentAssociationMappings)) {
            foreach ($this->parentAssociationMappings as $parentAssociationMapping) {
                $object = $this->getFieldValue($object, $parentAssociationMapping['fieldName']);

                if (is_null($object)) {
                    break;
                }
            }
        }

        if (is_null($object)) {
            $value = null;
        } else {
            try {
                $value = $this->getFieldValue($object, $this->fieldName);
            } catch (\Exception $e) {
                $value = '';
            }
        }

        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        // FIXME: use template for the rendering
        if (is_array($value)) {
            $value = implode(', ', $value);
        } elseif (is_null($value)) {
            $value = '';
        } else {
            switch ($this->getType()) {
                case 'datetime':
                    if ($value instanceof \DateTime) {
                        $value = $value->format($this->getOption('format', 'Y-m-d H:i:s'));
                    } else {
                        $value = '';
                    }
                    break;
                case 'date':
                    if ($value instanceof \DateTime) {
                        $value = $value->format($this->getOption('format', 'Y-m-d'));
                    } else {
                        $value = '';
                    }
                    break;
                case 'boolean':
                    $value = $value ? 'yes' : 'no';
                    break;
                case 'enum':
                $class = isset($this->options['class']) ? $this->options['class'] : null;
                $classPrefixed = isset($this->options['classPrefixed']) ? $this->options['classPrefixed'] : null;
                $namespaceSeparator = isset($this->options['namespaceSeparator']) ? $this->options['namespaceSeparator'] : null;

                if ($class) {
                    $value = array_search(
                        $value,
                        call_user_func([$class, 'getConstants'], 'strtolower', $classPrefixed, $namespaceSeparator)
                    );
                }
                    break;
            }
        }

        return $value;
    }
}
