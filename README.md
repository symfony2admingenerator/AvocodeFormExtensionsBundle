FormExtensions
==============

Symfony2 form extensions for Admingenerator project inspired by 
[genemu/GenemuFormBundle](https://github.com/genemu/GenemuFormBundle).

### Documentation

For a full list of form types and extensions (and related notes)
see [documentation](Resouces/documentation.md).

--------------

### Installation

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

### Translators needed!

We need your support to translate forms messages :) 
If you want to help open a pull request and submit a package for your language.

### License

For license information read carefully `LICENSE` file. 
