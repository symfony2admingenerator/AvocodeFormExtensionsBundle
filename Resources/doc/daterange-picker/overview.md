DateRangePicker
===============

[bootstrap-daterangepicker]: https://github.com/dangrossman/bootstrap-daterangepicker
The DateRangePickerType uses the 
[dangrossman/bootstrap-daterangepicker][bootstrap-daterangepicker]

```php
<?php 

class DemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('demoString', 'daterange_picker', array(
            'drp_translation_domain'  => 'AvocodeFormExtensions',
            'required'                => false,
            'use_daterange_entity'    => false,   // if a Avocode\FormExtensionsBundle\Form\Model\DateRange object is used as data it must be set to true, otherwise a string is used
            'opens'                   => 'right', // opens on the left or on the right
            'separator'               => ' - ',   // separator used between the two dates 
            'show_week_numbers'       => true,    // show the week numbers
            'show_dropdowns'          => false,   // show dropdowns for the months and year
            'min_date'                => null,    // min date
            'max_date'                => null,    // max date
            'date_limit'              => false,   // date limit: false or array('days' => 5) 
            'ranges'                  => null,    // ranges null or array 
          /* you can use predefined ranges
            'ranges'                    => array(   
                'today', 'yesterday', 'last-week', 'last-month', 'last-year'
            ),
           */
          /* or define your own
            'ranges'                    => array(
                'My custom range'   =>  array('2013-01-01', '2013-04-01'),
                'My custom range2'  =>  array('2012-09-01', '2013-02-01'),
            ),
           */
            // locale values
            'locale' => array(
                'applyLabel'        => 'Submit',
                'clearLabel'        => 'Clear',
                'fromLabel'         => 'From',
                'toLabel'           => 'To',
                'weekLabel'         => 'W',
                'customRangeLabel'  => 'Custom Range',
                'daysOfWeek'        => array('Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'),
                'monthNames'        => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                'firstDay'          => 1,
            ),
        ));
    }
}
```
