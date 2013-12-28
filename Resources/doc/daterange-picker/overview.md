# DateRange Picker
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

[symfony-datetype]: http://symfony.com/doc/current/reference/forms/types/date.html
[dangrossman-daterangepicker]: https://github.com/dangrossman/bootstrap-daterangepicker

### Form Type

 `afe_daterange_picker`
 
### Description

Use [dangrossman/bootstrap-daterangepicker][dangrossman-daterangepicker] as GUI
wrapper for two [DateType][symfony-datetype] fields (start date and end date).

### Options

##### drp_translation_domain

**type**: `integer|string`, **default**: `AvocodeFormExtensions`

Translation domain for DateRange Picker messages.

##### use_daterange_entity

**type**: `boolean`, **default**: `false`

If true Avocode\FormExtensionsBundle\Form\Model\DateRange object will be used as data,
otherwise string is used.

##### opens

**type**: `string`, **default**: `right`

Open the widget on the `left` or `right`.

##### separator

**type**: `string`, **default**: ` - `

Separator used between the two dates.

##### showWeekNumbers

**type**: `boolean`, **default**: `true`

Show the week numbers.

##### showDropdowns

**type**: `boolean`, **default**: `false`

Show dropdowns for the months and year.

##### minDate

**type**: `boolean|string`, **default**: `false`

String value representing the earliest date that may be selected, all earlier dates will
be disabled. The string should be in a format `dd/mm/yyyy`.

##### maxDate

**type**: `boolean|string`, **default**: `false`

String value representing the latest date that may be selected, all later dates will
be disabled. The string should be in a format `dd/mm/yyyy`.

##### dateLimit

**type**: `boolean|array`, **default**: `false`

Date limit: false or array('days'=>5)

##### ranges

**type**: `null|array`, **default**: `null`

You can use predefined ranges:

* `today`, `yesterday`, `last-week`, `last-month`, `last-year`

```php
$builder->add('demo', 'daterange_picker', array(
    'ranges' => array('today', 'yesterday')
);
```

Or define your own:

```php
$builder->add('demo', 'afe_daterange_picker', array(
    'ranges'                    => array(
        'My custom range'   =>  array('2013-01-01', '2013-04-01'),
        'My custom range2'  =>  array('2012-09-01', '2013-02-01'),
    ),
);
```