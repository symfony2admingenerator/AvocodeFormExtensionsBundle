FormExtensions
==============

### Translators needed!

We need your support to translate forms messages :) 
If you want to help open a pull request and submit a package for your language.

--------------

Symfony2 form extensions for Admingenerator project inspired by 
[genemu/GenemuFormBundle](https://github.com/genemu/GenemuFormBundle).

#### This bundle provides new form types:

* Bootstrap Collection family: `collection_fieldset`, `collection_table`
* Collection Upload: `collection_upload`
* Date and Time family: `date_picker`, `time_picker`, `datetime_picker`, `daterange_picker`
* Double List family: `double_list_document`, `double_list_entity`, `double_list_model`
* Single Upload: `single_upload`
* Color Picker: `color_picker`
* Select2 family: `select2_choice`, `select2_language`, `select2_country`, `select2_timezone`, `select2_locale`, `select2_entity`, `select2_document`, `select2_model`

#### and new form extensions:

* AutocompleteExtension
* HelpMessageExtension

Forms that will be added to this bundle:
* Chosen
* Knob
* Rate

#### Installation

Add this to your `composer.json`:

```json
    "require": {
        // ...
        "avocode/form-extensions-bundle": "dev-master"
    }
```

And then enable the bundle in your `AppKernel.php`:

```php
<?php
    // AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Avocode\FormExtensionsBundle\AvocodeFormExtensionsBundle(),
        );
    }
```

To make `avocode/form-extensions-bundle` forms work, you need to edit your base 
template, and include static and dynamic stylesheets and javascripts. 

```html+django
{% block stylesheets %}
    {{ parent() }}

    {% include 'AvocodeFormExtensionsBundle::stylesheets.html.twig' %}
    {% if form is defined %}
        {{ form_stylesheet(form) }}
    {% endif %}
{% endblock %}

{% block javascripts %}
    {{ parent() }}

    {% include 'AvocodeFormExtensionsBundle::javascripts.html.twig' %}
    {% if form is defined %}
        {{ form_javascript(form) }}
    {% endif %}
{% endblock %}
```

#### Note

Documentation for all form types will be gradually filled. You can find it 
in `Resources/doc` directory

#### License

For license information read carefully `LICENSE` file. 
