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
 * See `Resources/doc/daterange-picker/overview.md` for documentation
 *
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
                if ("today" === $key || "today" === $value) {
                    $key = "today";
                    $today = date('Y-m-d');
                    $value = array($today, $today);
                }

                if ("yesterday" === $key || "yesterday" === $value) {
                    $key = "yesterday";
                    $yesterday = date('Y-m-d', strtotime('-1 days'));
                    $value = array($yesterday, $yesterday);
                }

                if ("last-week" === $key || "last-week" === $value) {
                    $key = "last-week";
                    $lastWeek = date('Y-m-d', strtotime('last week'));
                    $today = date('Y-m-d');
                    $value = array($lastWeek, $today);
                }

                if ("last-month" === $key || "last-month" === $value) {
                    $key = "last-month";
                    $lastMonth = date('Y-m-d', strtotime('last month'));
                    $today = date('Y-m-d');
                    $value = array($lastMonth, $today);
                }

                if ("last-year" === $key || "last-year" === $value) {
                    $key = "last-year";
                    $ago1years = date('Y-m-d', strtotime('last year'));
                    $today = date('Y-m-d');
                    $value = array($ago1years, $today);
                }

                if (in_array($key, array(
                    "today", "yesterday", "last-week", "last-month", "last-year"
                ))) {
                    $key_tr = $this->translator->trans('date_range.ranges.'.$key, array(), $options['drp_translation_domain']);
                    $ranges[$key_tr] = $value;
                } else {
                    $ranges[$key] = $value;
                }
            }
        }

        $view->vars = array_merge(
            $view->vars,
            array(
                'formatSubmit'    => $options['formatSubmit'],
                'opens'           => $options['opens'],
                'separator'       => $options['separator'],
                'showWeekNumbers' => $options['showWeekNumbers'],
                'showDropdowns'   => $options['showDropdowns'],
                'minDate'         => $options['minDate'],
                'maxDate'         => $options['maxDate'],
                'dateLimit'       => $options['dateLimit'],
                'ranges'          => $ranges,
                'locale'          => $locale,
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'drp_translation_domain'  => 'AvocodeFormExtensions',
            'use_daterange_entity'    => false,
            'format'                  => 'yyyy-MM-dd',
            'formatSubmit'            => 'yyyy-mm-dd',
            'opens'                   => 'right',
            'separator'               => ' - ',
            'showWeekNumbers'         => true,
            'showDropdowns'           => false,
            'minDate'                 => false,
            'maxDate'                 => false,
            'dateLimit'               => false,
            'ranges'                  => false,
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
            'showWeekNumbers' => array('bool'),
            'showDropdowns'   => array('bool'),
            'minDate'         => array('bool', 'string'),
            'maxDate'         => array('bool', 'string'),
            'ranges'          => array('bool', 'array'),
        ));

        $resolver->setAllowedValues(array(
            'opens' => array('left', 'right'),
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
