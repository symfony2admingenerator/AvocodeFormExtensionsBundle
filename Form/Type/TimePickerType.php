<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class TimePickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'minute_step'   => json_encode($options['minute_step']),
            'second_step'   => json_encode($options['second_step']),
            'default_time'  => json_encode($options['default_time']),
            'show_meridian' => json_encode($options['show_meridian']),
            'disable_focus' => json_encode($options['disable_focus']),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget'        => 'single_text',
            'minute_step'   => 15,
            'second_step'   => 15,
            'default_time'  => 'current',
            'show_meridian' => false,
            'disable_focus' => false,
            'attr'          => array(
                'class' => 'input-small',
            ),
        ));

        $resolver->setAllowedTypes(array(
            'minute_step'     => array('integer'),
            'second_step'     => array('integer'),
            'default_time'    => array('string', 'bool'),
            'show_meridian'   => array('bool'),
            'disable_focus'   => array('bool'),
        ));
    }

    public function getParent()
    {
        return 'time';
    }

    public function getName()
    {
        return 'time_picker';
    }
}