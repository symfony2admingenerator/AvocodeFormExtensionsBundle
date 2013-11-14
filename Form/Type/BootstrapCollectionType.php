<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * See `Resources/doc/bootstrap-collection/overview.md` for documentation
 *
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class BootstrapCollectionType extends AbstractType
{
    private $widget;

    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['sortable']       = $options['sortable'];
        $view->vars['sortable_field'] = $options['sortable_field'];
        $view->vars['new_label']      = $options['new_label'];
        $view->vars['prototype_name'] = $options['prototype_name'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'sortable'        => false,
            'sortable_field'  => 'position',
            'new_label'       => 'afe_collection.new_label'
        ));

        $resolver->setAllowedTypes(array(
            'sortable'        => array('bool'),
            'sortable_field'  => array('string'),
            'new_label'       => array('string'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'afe_collection_' . $this->widget;
    }
}
