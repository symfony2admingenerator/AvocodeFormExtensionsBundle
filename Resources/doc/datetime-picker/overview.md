DateTime Picker
===============

[bootstrap-datepicker]: http://www.eyecon.ro/bootstrap-datepicker/
[bootstrap-timepicker]: http://jdewit.github.io/bootstrap-timepicker/

The DateTimePickerType uses the [bootstrap-datepicker][bootstrap-datepicker] 
and [bootstrap-timepicker][bootstrap-timepicker]

```php
<?php

class DemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('demoDatetime', datetime_picker', array(
            'week_start'      => 0,       // day of the week start. 0 for Sunday - 6 for Saturday
            'view_mode'       => 'days',  // set the start view mode. Accepts: 'days', 'months', 'years', 0 for days, 1 for months and 2 for years
            'min_view_mode'   => 'days',  // set a limit for view mode. Accepts: 'days', 'months', 'years', 0 for days, 1 for months and 2 for years
            'minute_step'     => 15,      // Specify a step for the minute field.
            'second_step'     => 15,      // Specify a step for the second field.
            'with_seconds'    => false,   // Show the seconds field.
            'disable_focus'   => true,    // Disables the input from focusing. This is useful for touch screen devices that display a keyboard on input focus.
        ));
    }

}
```