# Time Picker
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

[symfony-timetype]: http://symfony.com/doc/current/reference/forms/types/time.html
[jdewit-timepicker]: https://github.com/jdewit/bootstrap-timepicker

### Form Type

 `afe_time_picker`

### Description

Use [jdewit/bootstrap-timepicker][jdewit-timepicker] as GUI wrapper for
[TimeType][symfony-timetype].

### Options

##### minute_step

**type**: `integer`, **default**: `15`

Specify a step for the minute field.

##### second_step

**type**: `integer`, **default**: `15`

Specify a step for the second field.

##### with_seconds

**type**: `integer`, **default**: `false`

Inherited from [TimeType][symfony-timetype]. Whether or not show the seconds field.

##### default_time

**type**: `string|boolean`, **default**: `current`

* `current` set default time to the current time
* `11:45 AM` set default time to a specific time
* `false` do not set a default time 

##### show_meridian

**type**: `boolean`, **default**: `false`

If true, use 12hr mode, otherwise use 24hr mode.

##### disable_focus

**type**: `integer`, **default**: `true`

Disables the input from focusing. This is useful for touch screen devices 
that display a keyboard on input focus.
