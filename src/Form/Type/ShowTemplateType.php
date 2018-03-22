<?php

namespace MMC\SonataAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShowTemplateType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['template'] = $options['template'];
        $view->vars['template_parameters'] = $options['template_parameters'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['template']);

        $resolver->setDefaults([
            'template_parameters' => [],
        ]);
    }
}
