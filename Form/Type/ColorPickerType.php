<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ColorType working with jQuery MiniColors 2.0 :
 *     http://labs.abeautifulsite.net/jquery-miniColors/
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
        $view->vars['configs'] = array();

        foreach (array(
            'animationSpeed',
            'animationEasing',
            'changeDelay',
            'control',
            'hideSpeed',
            'inline',
            'letterCase',
            'opacity',
            'position',
            'showSpeed',
            'swatchPosition',
            'textfield',
            'theme'
        ) as $option) {
            $view->vars['configs'][$option] = $options[$option];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'animationSpeed'  => 100,           // The animation speed of the sliders when the user taps or clicks a new color. Set to 0 for no animation.
            'animationEasing' => 'swing',       // The easing to use when animating the sliders.
            'changeDelay'     => 0,             // The time, in milliseconds, to defer the change event from firing while the user makes a selection
            'control'         => 'hue',         // Determines the type of control.
            'hideSpeed'       => 100,           // The speed at which to hide the color picker.
            'inline'          => false,         // Set to true to force the color picker to appear inline.
            'letterCase'      => 'lowercase',   // Determines the letter case of the hex code value.
            'opacity'         => false,         // Set to true to enable the opacity slider.
            'position'        => 'default',     // Sets the position of the dropdown.
            'showSpeed'       => 100,           // The speed at which to show the color picker.
            'swatchPosition'  => 'left',        // Determines which side of the textfield the color swatch will appear.
            'textfield'       => true,          // Whether or not to show the textfield.
            'theme'           => 'bootstrap'    // A string containing the name of the custom theme to be applied.
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
        return 'color_picker';
    }
}
