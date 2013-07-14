<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Avocode\FormExtensionsBundle\Form\DataTransformer\DateRangeToStringTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DateRangePickerType extends AbstractType
{
    protected $translator;

    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['use_daterange_entity']) {
            $builder->addModelTransformer(new DateRangeToStringTransformer($options['separator']));
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $locale = array(
            'applyLabel'        => $this->translator->trans($options['locale']['applyLabel'], array(), $options['drp_translation_domain']),
            'clearLabel'        => $this->translator->trans($options['locale']['clearLabel'], array(), $options['drp_translation_domain']),
            'fromLabel'         => $this->translator->trans($options['locale']['fromLabel'], array(), $options['drp_translation_domain']),
            'toLabel'           => $this->translator->trans($options['locale']['toLabel'], array(), $options['drp_translation_domain']),
            'weekLabel'         => $this->translator->trans($options['locale']['weekLabel'], array(), $options['drp_translation_domain']),
            'customRangeLabel'  => $this->translator->trans($options['locale']['customRangeLabel'], array(), $options['drp_translation_domain']),
            'daysOfWeek'        => array(
                $this->translator->trans($options['locale']['daysOfWeek'][0], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['daysOfWeek'][1], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['daysOfWeek'][2], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['daysOfWeek'][3], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['daysOfWeek'][4], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['daysOfWeek'][5], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['daysOfWeek'][6], array(), $options['drp_translation_domain']),
            ),
            'monthNames'        => array(
                $this->translator->trans($options['locale']['monthNames'][0], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][1], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][2], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][3], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][4], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][5], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][6], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][7], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][8], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][9], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][10], array(), $options['drp_translation_domain']),
                $this->translator->trans($options['locale']['monthNames'][11], array(), $options['drp_translation_domain']),
            ),
            'firstDay'          => intval($this->translator->trans($options['locale']['firstDay'], array(), $options['drp_translation_domain'])),
        );
        
        $ranges = array();
        if (is_array($options['ranges'])) {
            foreach ($options['ranges'] as $key => $value) {
                $key_tr = $this->translator->trans($key, array(), $options['drp_translation_domain']);
                $ranges[$key_tr] = $value;
            }
        }
        
        $view->vars = array_replace($view->vars, array(
            'opens'             => $options['opens'],
            'separator'         => $options['separator'],
            'show_week_numbers' => json_encode($options['show_week_numbers']),
            'show_dropdowns'    => json_encode($options['show_dropdowns']),
            'min_date'          => $options['min_date'],
            'max_date'          => $options['max_date'],
            'date_limit'        => json_encode($options['date_limit']),
            'ranges'            => json_encode($ranges),
            'locale'            => json_encode($locale),
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'drp_translation_domain'  => 'AvocodeFormExtensions', // translation domain
            'use_daterange_entity'    => false,   // set to true if DateRange object is used
            'opens'                   => 'right', // open on the left or right
            'separator'               => ' - ',   // separator used between the two dates
            'show_week_numbers'       => true,
            'show_dropdowns'          => false,   // show dropdowns for the months and year
            'min_date'                => null,    // null or string in format dd/mm/yyyy
            'max_date'                => null,    // null or string in format dd/mm/yyyy
            'date_limit'              => false,   // date limit: false or array('days'=>5)
            'ranges'                  => null,    // ranges null or array
            'locale' => array(
                'applyLabel'        => 'date_range.label.apply',
                'clearLabel'        => 'date_range.label.clear',
                'fromLabel'         => 'date_range.label.from',
                'toLabel'           => 'date_range.label.to',
                'weekLabel'         => 'date_range.label.week',
                'customRangeLabel'  => 'date_range.label.custom_range',
                'daysOfWeek'        => array(
                    'date_range.days.sunday', 
                    'date_range.days.monday', 
                    'date_range.days.tuesday', 
                    'date_range.days.wednesday', 
                    'date_range.days.thursday', 
                    'date_range.days.friday', 
                    'date_range.days.saturday',
                ),
                'monthNames'        => array(
                    'date_range.months.january',
                    'date_range.months.february',
                    'date_range.months.march',
                    'date_range.months.april',
                    'date_range.months.may',
                    'date_range.months.june',
                    'date_range.months.july',
                    'date_range.months.august',
                    'date_range.months.september',
                    'date_range.months.october',
                    'date_range.months.november',
                    'date_range.months.december',
                ),
                'firstDay'          => "1",
            ),
        ));

        $resolver->setAllowedTypes(array(
            'show_week_numbers' => array('bool'),
            'show_dropdowns'    => array('bool'),
            'min_date'          => array('null', 'string'),
            'max_date'          => array('null', 'string'),
        ));

        $resolver->setAllowedValues(array(
            'opens'             => array('left', 'right'),
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'daterange_picker';
    }
}