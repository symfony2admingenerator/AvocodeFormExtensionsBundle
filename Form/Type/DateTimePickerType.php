<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Avocode\FormExtensionsBundle\Form\DataTransformer\DateTimeToPartsTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 */
class DateTimePickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateOptions = array_intersect_key($options, array_flip(array(
            'week_start',
            'calendar_weeks',
            'start_date',
            'end_date',
            'days_of_week_disabled',
            'autoclose',
            'start_view',
            'min_view_mode',
            'today_btn',
            'today_highlight',
            'clear_btn',
            'language',
        )));

        $timeOptions = array_intersect_key($options, array_flip(array(
            'minute_step',
            'second_step',
            'disable_focus',
            'with_seconds',
        )));
        
        $builder
            ->resetViewTransformers()
            ->remove('date')
            ->remove('time')
            ->addViewTransformer(new DateTimeToPartsTransformer())
            ->add('date', 'bootstrap_datepicker', $dateOptions)
            ->add('time', 'bootstrap_timepicker', $timeOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'week_start'    => $options['week_start'],
            'view_mode'     => $options['view_mode'],
            'min_view_mode' => $options['min_view_mode'],
            'minute_step'   => $options['minute_step'],
            'second_step'   => $options['second_step'],
            'disable_focus' => $options['disable_focus'],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {

        $resolver->setDefaults(array(
            'format'          => 'yyyy-MM-dd',
            'week_start'      => 1,
            'view_mode'       => 0,
            'min_view_mode'   => 0,
            'minute_step'     => 15,
            'second_step'     => 15,
            'disable_focus'   => false,
            'attr'            => array(
                'class' => 'input-small'
            ),
        ));

        $resolver->setAllowedValues(array(
            'week_start'      => range(0, 6),
            'view_mode'       => array(0, 'days', 1, 'months', 2, 'years'),
            'min_view_mode'   => array(0, 'days', 1, 'months', 2, 'years'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'datetime';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'datetime_picker';
    }
}
