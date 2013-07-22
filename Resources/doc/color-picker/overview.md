# Color Picker
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

### 1. Overview

[jquery-minicolors]: http://labs.abeautifulsite.net/jquery-miniColors/

The ColorPickerType use the [jQuery MiniColors plugin][jquery-minicolors] in version 
78287fbbbd3c778897cb973bf94a63ad4dc7d818

### 2. Usage

```php
<?php 

class DemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // The simple way
        $builder->add('defaultColor', 'color_picker');
        // With options (here, options are default values, so demoColor and defaultColor fields will render the same)
        $builder->add(
            'demoColor',
            'color_picker',
            array(
                'animationSpeed'  => 100,           // The animation speed of the sliders when the user taps or clicks a new color. Set to 0 for no animation.
                'animationEasing' => 'swing',       // The easing to use when animating the sliders.
                'changeDelay'     => 0,             // The time, in milliseconds, to defer the change event from firing while the user makes a selection
                'control'         => 'hue',         // Determines the type of control.
                'hideSpeed'       => 100,           // The speed at which to hide the color picker.
                'inline'          => false,         // Set to true to force the color picker to appear inline.
                'letterCase'      => 'lowercase',   // Determines the letter case of the hex code value.
                'opacity'         => false,         // Set to true to enable the opacity slider.
                'position'        => 'default',     // Sets the position of the dropdown.
                'showSpeed'       => 100,           // The speed at which to show the color picker.
                'swatchPosition'  => 'left',        // Determines which side of the textfield the color swatch will appear.
                'textfield'       => true,          // Whether or not to show the textfield.
                'theme'           => 'bootstrap'    // A string containing the name of the custom theme to be applied.
            )
        );
    }
}
```

See [jQuery MiniColors documentation][jquery-minicolors] for more information about available configurations.


Data is saved into HEX format with `#` caracter as a first parameter.

### 3. Events

When user change the value, an event `colored` is triggered on original input. You can so add any handler you want:

```js
    $('.type-color').on('colored', function(event, input, hex, opacity) { 
        console.log('%o', arguments); 
    });
```