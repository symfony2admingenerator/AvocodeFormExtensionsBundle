# DateTime Picker
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

[symfony-datetimetype]: http://symfony.com/doc/current/reference/forms/types/datetime.html
[eternicode-datepicker]: https://github.com/eternicode/bootstrap-datepicker
[jdewit-timepicker]: https://github.com/jdewit/bootstrap-timepicker

### Form Type

 `afe_datetime_picker`
 
### Description

Use [eternicode/bootstrap-datepicker][eternicode-datepicker] and
[jdewit/bootstrap-timepicker][jdewit-timepicker] as GUI wrapper for
[DateTimeType][symfony-datetimetype].

### Options

##### date_format

**type**: `integer|string`, **default**: `yyyy-MM-dd`

Inherited from [DateTimeType][symfony-datetimetype]. This is the format used by 
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

##### minute_step

**type**: `integer`, **default**: `15`

Specify a step for the minute field.

##### second_step

**type**: `integer`, **default**: `15`

Specify a step for the second field.

##### with_seconds

**type**: `integer`, **default**: `false`

Inherited from [DateTimeType][symfony-datetimetype]. Whether or not show the seconds field.

##### disable_focus

**type**: `integer`, **default**: `true`

Disables the input from focusing. This is useful for touch screen devices 
that display a keyboard on input focus.
