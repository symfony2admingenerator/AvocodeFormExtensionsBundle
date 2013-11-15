# Date Picker
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

[symfony-datetype]: http://symfony.com/doc/current/reference/forms/types/date.html
[eternicode-datepicker]: https://github.com/eternicode/bootstrap-datepicker

### Form Type

 `afe_date_picker`
 
### Description

Use [eternicode/bootstrap-datepicker][eternicode-datepicker] as GUI wrapper for
[DateType][symfony-datetype].

### Options

##### format

**type**: `integer|string`, **default**: `yyyy-MM-dd`

Inherited from [DateType][symfony-datetype]. This is the format used by 
application logic.

##### formatSubmit

**type**: `string`, **default**: `yyyy-mm-dd`

The date format, combination of *d, dd, D, DD, m, mm, M, MM, yy, yyyy*:

* `d`: Day of the month, one or two digit, eg. `9`
* `dd`: Day of the month, two digit, eg. `09`
* `D`: Day of the week, localized, abbreviated, three chars, eg. `Mon`
* `DD`: Day of the week, localized, complete, eg. `Monday`
* `m`: Month, one or two digit, eg. `2`
* `mm`: Month, two digit, eg. `02`
* `M`: Month, localized, abbreviated, eg. `Feb`
* `MM`: Month, localized, complete, eg. `February`
* `yy`: Year, at least two digit, eg. `12`
* `yyyy`: Year, at least four digit, eg. `2012`

##### weekStart

**type**: `integer`, **default**: `1`

Day of the week start:

* `0`: Sunday
* `1`: Monday
* `2`: Tuesday
* `3`: Wednesday
* `4`: Thursday
* `5`: Friday
* `6`: Saturday

##### calendarWeeks

**type**: `boolean`, **default**: `false`

Whether or not to show week numbers to the left of week rows.

##### startDate

**type**: `string`, **default**: 20 years ago

String value representing the earliest date that may be selected, all earlier dates will
be disabled. The string should be in a format recognized by the [parse][date-parse] method 
([IETF-compliant RFC 2822 timestamps][RFC-2822]).

[date-parse]: https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Date/parse
[RFC-2822]: http://tools.ietf.org/html/rfc2822#page-14

##### endDate

**type**: `string`, **default**: 20 years from now

String value representing the latest date that may be selected, all later dates will
be disabled. The string should be in a format recognized by the [parse][date-parse] method 
([IETF-compliant RFC 2822 timestamps][RFC-2822]).

##### disabled

**type**: `array`, **default**: `array()`

Array of numeric representation of days of the week that should be disabled:

* `0`: Sunday
* `1`: Monday
* `2`: Tuesday
* `3`: Wednesday
* `4`: Thursday
* `5`: Friday
* `6`: Saturday

##### autoclose

**type**: `boolean`, **default**: `true`

Whether or not to close the datepicker immediately when a date is selected.

##### startView

**type**: `integer|string`, **default**: `month`

Start view mode:

* `0` or `month` display days
* `1` or `year` display months
* `2` or `decade` display years

##### minViewMode

**type**: `string`, **default**: `days`

Minimum view mode:

* `0` or `days` display days
* `1` or `months` display months
* `2` or `years` display years

##### todayButton

**type**: `boolean`, **default**: `false`

If true or "linked", displays a "Today" button at the bottom of the 
datepicker to select the current date.

##### todayHighlight

**type**: `boolean`, **default**: `false`

If true, highlights the current date.

##### clearButton

**type**: `boolean`, **default**: `false`

If true, displays a "Clear" button at the bottom of the datepicker to clear the input value. 
If `autoclose` is also set to true, this button will also close the datepicker.

##### language

**type**: `string`, **default**: value of `%locale%` parameter

Choose widget language.
