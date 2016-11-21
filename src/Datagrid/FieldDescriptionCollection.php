<?php

namespace MMC\SonataAdminBundle\Datagrid;

use Sonata\AdminBundle\Admin\BaseFieldDescription;
use Sonata\AdminBundle\Admin\FieldDescriptionCollection as BaseFieldDescriptionCollection;

class FieldDescriptionCollection extends BaseFieldDescriptionCollection
{
    private $options;

    public function __construct($options = [])
    {
        $this->options = $options;

        if (!isset($this->options['translation_domain'])) {
            $this->options['translation_domain'] = 'messages';
        }

        if (!isset($options['label_pattern'])) {
            $options['label_pattern'] = '%s';
        }
    }

    public function addField($name, $options = [])
    {
        $fieldDescription = $options;
        if (!$fieldDescription instanceof BaseFieldDescription) {
            $fieldDescription = new DTOFieldDescription($name, $options);
        }

        $options = $fieldDescription->getOptions();

        // Build missing options
        if (!isset($options['label'])) {
            // Build label
            $options['label'] = $name;
        }

        $options['label'] = sprintf($this->options['label_pattern'], $options['label']);

        if (!isset($options['translation_domain'])) {
            $options['translation_domain'] = $this->options['translation_domain'];
        }

        $fieldDescription->setOptions($options);
        $this->add($fieldDescription);

        return $this;
    }
}
