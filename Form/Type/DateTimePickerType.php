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
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DateTimePickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateOptions = array_intersect_key($options, array_flip(array(
            'format',
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
            'with_seconds',
            'second_step',
            'default_time',
            'show_meridian',
            'disable_focus',
        )));
        
        $builder
            ->resetViewTransformers()
            ->remove('date')
            ->remove('time')
            ->addViewTransformer(new DateTimeToPartsTransformer())
            ->add('date', 'date_picker', $dateOptions)
            ->add('time', 'time_picker', $timeOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'week_start'    => $options['week_start'],
            'start_view'    => $options['start_view'],
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
            'start_view'      => 0,
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
            'start_view'      => array(0, 'month', 1, 'year', 2, 'decade'),
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
