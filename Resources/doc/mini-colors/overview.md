# Mini Colors
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

[jquery-minicolors]: http://labs.abeautifulsite.net/jquery-miniColors/
[symfony-texttype]: http://symfony.com/doc/current/reference/forms/types/text.html

### Description

Use [jquery-miniColors][jquery-minicolors] as GUI wrapper for
[TextType][symfony-texttype] field allowing user to select HEX color code.

> **Note:** Data is saved into HEX format with `#` caracter as a first parameter.

### Options

##### animationSpeed

**type**: `integer`, **default**: `100`

The animation speed of the sliders when the user taps or clicks a new color. 
Set to 0 for no animation.

##### animationEasing

**type**: `string`, **default**: `swing`

The easing to use when animating the sliders. For possible values and demos see
[jQuery easings reference](http://easings.net).

##### changeDelay

**type**: `integer`, **default**: `0`

The time, in milliseconds, to defer the change event from firing while 
the user makes a selection.

##### control

**type**: `string`, **default**: `hue`

Determines the type of control. Possible values:

* `hue`
* `brightness`
* `saturation`
* `wheel`

##### hideSpeed

**type**: `integer`, **default**: `100`

The speed at which to hide the widget.

##### inline

**type**: `boolean`, **default**: `false`

Set to true to force the widget to appear inline.

##### letterCase

**type**: `string`, **default**: `lowercase`

Determines the letter case of the hex code value. Possible values:

* `uppercase`
* `lowercase`

##### opacity

**type**: `boolean`, **default**: `false`

Set to true to enable the opacity slider.

##### position

**type**: `string`, **default**: `default`

Sets the position of the dropdown. Possible values:

* `default`
* `top`
* `left`
* `top left`

##### showSpeed

**type**: `integer`, **default**: `100`

The speed at which to show the widget.

##### swatchPosition

**type**: `string`, **default**: `left`

Determines which side of the textfield the color swatch will appear. Possible values:

* `left`
* `right`

##### textfield

**type**: `boolean`, **default**: `true`

Whether or not to show the textfield.

##### theme

**type**: `string`, **default**: `bootstrap`

A string containing the name of the custom theme to be applied.

### Events

When user change the value, an event `colored` is triggered on original input. 
You can so add any handler you want:

```js
$('.type-color').on('colored', function(event, input, hex, opacity) { 
    console.log('%o', arguments); 
});
```