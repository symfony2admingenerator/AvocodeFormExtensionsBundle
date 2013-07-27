<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * See `Resources/doc/mini-colors/overview.md` for documentation
 * 
 * @author Escandell Stéphane <stephane.escandell@gmail.com>
 */
class ColorPickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge($view->vars, array(
            'configs' => array(
                'animationSpeed'  => $options['animationSpeed'],
                'animationEasing' => $options['animationEasing'],
                'changeDelay'     => $options['changeDelay'],
                'control'         => $options['control'],
                'hideSpeed'       => $options['hideSpeed'],
                'inline'          => $options['inline'],
                'letterCase'      => $options['letterCase'],
                'opacity'         => $options['opacity'],
                'position'        => $options['position'],
                'showSpeed'       => $options['showSpeed'],
                'swatchPosition'  => $options['swatchPosition'],
                'textfield'       => $options['textfield'],
                'theme'           => $options['theme'],
            )
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'animationSpeed'  => 100,
            'animationEasing' => 'swing',
            'changeDelay'     => 0,
            'control'         => 'hue',
            'hideSpeed'       => 100,
            'inline'          => false,
            'letterCase'      => 'lowercase',
            'opacity'         => false,
            'position'        => 'default',
            'showSpeed'       => 100,
            'swatchPosition'  => 'left',
            'textfield'       => true,
            'theme'           => 'bootstrap'
        ));

        $resolver->setAllowedValues(array(
            'control'        => array('hue', 'brightness', 'saturation', 'wheel'),
            'letterCase'     => array('lowercase', 'uppercase'),
            'position'       => array('default', 'top', 'left', 'top left'),
            'swatchPosition' => array('left', 'right')
        ));

        $resolver->setAllowedTypes(array(
            'animationSpeed'  => 'integer',
            'animationEasing' => 'string',
            'changeDelay'     => 'integer',
            'hideSpeed'       => 'integer',
            'inline'          => 'bool',
            'opacity'         => 'bool',
            'showSpeed'       => 'integer',
            'textfield'       => 'bool',
            'theme'           => 'string'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mini_colors';
    }
}
