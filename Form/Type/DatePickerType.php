<?php

namespace Avocode\FormExtensionsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Vincent Touzet <vincent.touzet@gmail.com>
 * @author Piotr Gołębiewski <loostro@gmail.com>
 */
class DatePickerType extends AbstractType
{
    private $locale;

    private static $acceptedFormats = array(
        \IntlDateFormatter::FULL,
        \IntlDateFormatter::LONG,
        \IntlDateFormatter::MEDIUM,
        \IntlDateFormatter::SHORT,
    );
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $dateFormat = \IntlDateFormatter::MEDIUM;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = $options['format'];

        if (!in_array($dateFormat, self::$acceptedFormats, true)) {
            throw new InvalidOptionsException('The "format" option must be one of the IntlDateFormatter constants (FULL, LONG, MEDIUM, SHORT) or a string representing a custom format.');
        }

        $builder->addViewTransformer(new DateTimeToLocalizedStringTransformer(
            null,
            null,
            $dateFormat,
            $timeFormat,
            $calendar,
            $pattern
        ));

        if ( $options['input'] == 'single_text' ) {
            $builder->addModelTransformer(new ReversedTransformer(
                new DateTimeToStringTransformer(null, null, 'Y-m-d')
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $today_btn = $options['today_btn'];
        
        if (is_bool($today_btn)) {
            $today_btn = json_encode($today_btn);
        }
        
        $language = $options['language'];
        
        if ($language === false) {
            $language = $this->getLocale();
        }
        
        $view->vars['format']                 = $options['format'];        
        $view->vars['week_start']             = $options['week_start'];
        $view->vars['calendar_weeks']         = $options['calendar_weeks'];
        $view->vars['start_date']             = $options['start_date'];
        $view->vars['end_date']               = $options['end_date'];
        $view->vars['days_of_week_disabled']  = json_encode($options['days_of_week_disabled']);
        $view->vars['autoclose']              = $options['autoclose'];
        $view->vars['start_view']             = $options['start_view'];
        $view->vars['view_mode']              = $options['view_mode'];
        $view->vars['min_view_mode']          = $options['min_view_mode'];
        $view->vars['today_btn']              = $today_btn;
        $view->vars['today_highlight']        = $options['today_highlight'];
        $view->vars['clear_btn']              = $options['clear_btn'];
        $view->vars['language']               = $language;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'input'                 => 'datetime',
            'format'                => 'yyyy-MM-dd',
            'week_start'            => 1,
            'calendar_weeks'        => false,
            'start_date'            => date('Y-m-d', strtotime('-20 years')),
            'end_date'              => date('Y-m-d', strtotime('+20 years')),
            'days_of_week_disabled' => array(),
            'autoclose'             => true,
            'start_view'            => 0,
            'view_mode'             => 0,
            'min_view_mode'         => 0,
            'today_btn'             => false,
            'today_highlight'       => false,
            'clear_btn'             => false,
            'language'              => false,
            'attr'                  => array(
                'class' => 'input-small'
            ),
        ));

        $resolver->setAllowedTypes(array(
            'calendar_weeks'        => array('bool'),
            'days_of_week_disabled' => array('array'),
            'autoclose'             => array('bool'),
            'today_highlight'       => array('bool'),
            'clear_btn'             => array('bool'),
        ));

        $resolver->setAllowedValues(array(
            'week_start'      => range(0, 6),
            'start_view'      => array(0, 'month', 1, 'year', 2, 'decade'),
            'view_mode'       => array(0, 'days', 1, 'months', 2, 'years'),
            'min_view_mode'   => array(0, 'days', 1, 'months', 2, 'years'),
            'today_btn'       => array(true, false, 'linked'),
        ));
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'date_picker';
    }

    /**
     * Gets Locale
     * 
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
    
    /**
     * Sets Locale
     * 
     * @param string $locale Locale
     * @return string
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        
        return $this;
    }
}