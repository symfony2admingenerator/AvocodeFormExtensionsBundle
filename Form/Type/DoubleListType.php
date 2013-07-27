<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * See `Resources/doc/double-list/overview.md` for documentation
 * 
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DoubleListType extends AbstractType
{
    private $widget;

    public function __construct($widget)
    {
        $this->widget = $widget;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'multiple'    => true,
            'attr'        => array(
                'class' => 'hidden-select',
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return $this->widget;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'double_list_' . $this->widget;
    }
}
