Date Picker
===========

[bootstrap-datepicker]: https://github.com/eternicode/bootstrap-datepicker

The DatePickerType use the [eternicode/bootstrap-datepicker][bootstrap-datepicker]

```php
<?php 

class DemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('demoDate', 'date_picker', array(
            'format'                => 'yyyy-MM-dd',  // format
            'week_start'            => 1,             // day of the week start. 0 for Sunday - 6 for Saturday 
            'calendar_weeks'        => false,         // Whether or not to show week numbers to the left of week rows
            'start_date'            => '',            // The earliest date that may be selected; all earlier dates will be disabled.
            'end_date'              => '',            // The latest date that may be selected; all later dates will be disabled.
            'days_of_week_disabled' => array(),       // Array of Days of the week that should be disabled. Values are 0 (Sunday) to 6 (Saturday).
            'autoclose'             => true,          // Whether or not to close the datepicker immediately when a date is selected
            'start_view'            => 'month',       // set the start view mode. Accepts: month', 'year', 'decade', 0 for month, 1 for year and 2 for decade
            'min_view_mode'         => 'days',        // set a limit for view mode. Accepts: 'days', 'months', 'years', 0 for days, 1 for months and 2 for years 
            'today_btn'             => false,         // If true or "linked", displays a "Today" button at the bottom of the datepicker to select the current date. 
                                                      // If true, the "Today" button will only move the current date into view; if "linked", the current date will also be selected.
            'today_highlight'       => false,         // If true, highlights the current date.
            'clear_btn'             => false,         // If true, displays a "Clear" button at the bottom of the datepicker to clear the input value. 
                                                      // If "autoclose" is also set to true, this button will also close the datepicker.
            'language'              => false,         // language to use. If false it use the %locale% parameter
        ));
    }
}
```