# HelpMessage extension
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/avocode/FormExtensions/blob/master/Resources/doc/documentation.md

### Description

HelpMessage extension adds `help` option to every form.

### Options

##### help

**type**: `null|string`, **default**: `null`

Specify help message string rendered next to form item.

##### Usage example

````
    fields:
        date:
            addFormOptions:
                help:           "Enter your help message here"
````

Requires installed & enabled `Avocode/FormExtensionsBundle`
